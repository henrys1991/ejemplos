# -*- encoding: utf-8 -*-
##############################################################################
#
#    OpenERP, Open Source Management Solution	
#    Copyright (C) 2004-2008 Tiny SPRL (<http://tiny.be>). All Rights Reserved
#    $Id$
#
#    This program is free software: you can redistribute it and/or modify
#    it under the terms of the GNU General Public License as published by
#    the Free Software Foundation, either version 3 of the License, or
#    (at your option) any later version.
#
#    This program is distributed in the hope that it will be useful,
#    but WITHOUT ANY WARRANTY; without even the implied warranty of
#    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#    GNU General Public License for more details.
#
#    You should have received a copy of the GNU General Public License
#    along with this program.  If not, see <http://www.gnu.org/licenses/>.
#
##############################################################################

from osv import fields, osv
import time
from datetime import datetime

class medical_order_lab(osv.osv):
    _name = 'medical.order.lab'
    #_inherit = 'medical.patient.lab.test'
    
    #metodo para calcular el total de la prueba
    def _calcular_total(self, cr, uid, ids, name, arg, context={}):
        suma = 0.00
        result = {}
        for test in self.browse(cr, uid, ids, context=context):
                for t in test.test_type_lab:                    
                     suma+= t.product_id.list_price              
                result[test.id]=suma                
        return result

    #metodo para calcular el total a cobrar de la prueba
    def _calcular_total_cobrar(self, cr, uid, ids, name, arg, context={}):
        suma = 0.00
        result = {}
        for test in self.browse(cr, uid, ids, context=context):
                for t in test.test_type_lab:                    
                     suma+= t.product_id.standard_price             
                result[test.id]=suma                
        return result
    
    
    _columns = {
    'name' : fields.char ('Nro Orden',size=64, readonly=True, required=True),
	'user_id': fields.many2one('res.users', 'Usuario', help="El usuario responsable para esta orden de laboratorio", required=True),
	'institution' : fields.many2one ('sale.shop','Centro Médico',help="Centro Medico donde se realiza la orden de laboratorio", required=True),
	'patient_id' : fields.many2one('medical.patient','Paciente', required=True),        
	'order_date' : fields.datetime('Fecha', required=True),
	'doctor_id' : fields.many2one('medical.physician','Doctor', help="Doctor who Request the lab test."),                
	'test_type_lab' : fields.many2many('medical.test_type','medical_order_test_rel','order_id','test_type_id','Examenes', required=True),
   	'valor_test' : fields.function(_calcular_total, type='float', method=True, store=True, string='Valor real', help='Valor total de la Prueba de Laboratorio'),
 	'valor_cobrar' : fields.function(_calcular_total_cobrar, type='float', method=True, store=True, string='Valor a cobrar', help='Valor a cobrar de la Prueba de Laboratorio'),
    	'invoice_status' :fields.char('Invoice Status', size=64),
	'cant': fields.float('Pacientes',),
#'state' : fields.selection([('draft','Draft'),('tested','Tested'),('cancel','Cancel')],'State',readonly=True),
  	    }
    
    _defaults={
       	       'valor_test' : 0.00,
	       'user_id': lambda self, cr, uid, context: uid, 
	       'institution': lambda self, cr, uid, context: self._institucion(cr,uid),
	       'order_date': lambda *a: time.strftime('%Y-%m-%d %H:%M:%S'),
               'name': lambda self, cr, uid, context: '/',
		'cant':lambda obj, cr, uid, context: 1,
        #self.pool.get('ir.sequence').get(cr, uid, 'medical.order.lab'),
          
       }

    def _institucion(self, cr, uid, context=None):  
        cr.execute("select shop_id from rel_user_shop where user_id=%s",(uid,))
        res = cr.fetchone()[0]        
        return int(res)
    
    def create(self, cr, user, vals, context=None):
        if ('name' not in vals) or (vals.get('name')=='/'):
            vals['name'] = self.pool.get('ir.sequence').get(cr, user, 'medical.order.lab')
        return super(medical_order_lab,self).create(cr, user, vals, context)



medical_order_lab()



