# -*- coding: utf-8 -*-
##############################################################################
#
#    OpenERP, Open Source Management Solution
#    Copyright (C) 2004-2010 Tiny SPRL (<http://tiny.be>).
#
#    This program is free software: you can redistribute it and/or modify
#    it under the terms of the GNU Affero General Public License as
#    published by the Free Software Foundation, either version 3 of the
#    License, or (at your option) any later version.
#
#    This program is distributed in the hope that it will be useful,
#    but WITHOUT ANY WARRANTY; without even the implied warranty of
#    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#    GNU Affero General Public License for more details.
#
#    You should have received a copy of the GNU Affero General Public License
#    along with this program.  If not, see <http://www.gnu.org/licenses/>.
#
##############################################################################

import time
from lxml import etree

from osv import fields, osv
from tools.translate import _


#REPORTE MENSUAL PARA LABORATORIO
class medical_laboratory_report(osv.osv_memory):
    _name = "medical.laboratory.report"
    _description = "Laboratory Report"

    _columns = {
        'month': fields.selection( [('01','Enero'), ('02','Febrero'), ('03','Marzo'), ('04','Abril'), ('05','Mayo'),('06','Junio'), ('07','Julio'), ('08','Agosto'), ('09','Septiembre'), ('10','Octubre'),('11','Noviembre'), ('12','Diciembre')], 'Mes', required=True,
            help="Seleccione el mes para obtener resultados de laboratorio"),
        'year':fields.many2one('account.fiscalyear', 'Año', help="Seleccione el año", required=True),
	}

    def _institucion(self, cr, uid, context=None):  
        cr.execute("select shop_id from rel_user_shop where user_id=%s",(uid,))
        res = cr.fetchone()[0]        
        return int(res)
    
    def _datos(self, cr, uid, ids, data, context=None):
        if context is None:
          context={}
        result ={}
     #   cr.execute("select s.name, count(s.name), sum(m.valor_cita) from sale_shop s, medical_appointment m " \
      #             "where m.institution=s.id and  (to_char(appointment_date, 'MM'))=%s " \
       #            "group by s.name", (data['form']['month'],))
        
        cr.execute("select s.name, count(s.name), sum(m.valor_test) from sale_shop s, medical_order_lab m " \
                   "where m.institution=s.id and  (to_char(order_date, 'MM')=%s and to_char(order_date,'YYYY')=%s) " \
                   "group by s.name", (data['form']['month'],data['form']['year'],))
        result = cr.fetchall()
        return result
    
    def check_report(self, cr, uid, ids, context=None):
        if context is None:
            context = {}
        data = {}
        bandera=True  
        
        
        data['form']=self.read(cr,uid,ids,['month','year'])[0]
        #obtengo el name del año
        data['form']['year'] = self.pool.get('account.fiscalyear').browse(cr, uid, data['form']['year']).name
        
        if (data['form']['month']==False):                        
            raise osv.except_osv(_('Warning !'), _('Seleccione el mes'))
            return True
        else:            
            data['res']=self._datos(cr, uid, ids, data, context)
            if data['res']==[]:
                data['res']=[['No se encontraron resultados','','','']]
                bandera=False
            data['total']=0
            if (bandera):
                for d in data['res']:
                    data['total']+=d[2]
        
        res={'01':'Enero', '02':'Febrero','03':'Marzo', '04':'Abril', '05':'Mayo', '06':'Junio', '07':'Junlio', '08':'Agosto', '09':'Septiembre','10':'Octubre', '11':'Noviembre', '12':'Diciembre'}
        for clave, val in res.items() :
            if clave == data['form']['month']:
                data['form']['month']= val                
        
        return {'type': 'ir.actions.report.xml', 'report_name': 'medical.laboratory.report', 'datas': data}

medical_laboratory_report()

