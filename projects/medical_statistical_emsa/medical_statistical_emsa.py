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
from dateutil.relativedelta import relativedelta
from datetime import datetime

class driver_driver(osv.osv):
    _name = 'driver.driver'
    _columns = {
            #'name':fields.char('Name', size=64, required=False, readonly=False),             
            'name':fields.many2one('hr.employee', 'Conductor', required=True),
            'active':fields.boolean('Activo', required=False), 
                    }
    _defaults = {  
        'active': lambda *a: True,  
        }
    
    def name_get(self, cr, uid, ids, context={}):        
        if not len(ids):
            return []
        rec_name = 'name'
        res = [(r['id'], r[rec_name][1]) for r in self.read(cr, uid, ids, [rec_name], context)]
        return res
    
driver_driver()

class paramedic_paramedic(osv.osv):
    _name = 'paramedic.paramedic'
    _columns = {
            #'name':fields.char('Name', size=64, required=False, readonly=False),             
            'name':fields.many2one('hr.employee', 'Paramedico', required=True),
            'active':fields.boolean('Activo', required=False), 
                    }
    _defaults = {  
        'active': lambda *a: True,  
        }
    
    def name_get(self, cr, uid, ids, context={}):          
        if not len(ids):
            return []
        rec_name = 'name'
        res = [(r['id'], r[rec_name][1]) for r in self.read(cr, uid, ids, [rec_name], context)]
        return res
    
paramedic_paramedic()

class material_material(osv.osv):
    _name = 'material.material'
    _columns = {
            'name':fields.char('Name', size=64, required=True, readonly=False),
                    }

material_material()


class medical_statistical_victim(osv.osv):
    #_name = "statiscal.victim"
    _inherit = "statistical.victim"
    _columns = {		                                
		            'cant'          : fields.float('Cant',),
                    'sector_id'     :fields.many2one('medical.sector', 'Sector', required=False),
                    'lastname'      :fields.char('Apellidos', size=64, required=False, readonly=False),                    
                    'date_arrival'  : fields.datetime('Hora de llegada'),
                    'material_id'   :fields.many2many('material.material', 'victim_material_rel', 'victim_id', 'material_id', 'Materiales'), 
                    'driver_id'     :fields.many2one('driver.driver', 'Conductor', required=False),   
                    'paramedic_id'  :fields.many2one('paramedic.paramedic', 'Paramedico', required=False),
                    'ubication'     :fields.char('Lugar del accidente', size=64, required=True, readonly=False),
                    #redefino el siguiente campo para q no sea requerido 
                    'bandera_id'    :fields.many2one('medical.bandera', 'Ubicacion', required=False),
               }
    
    _defaults = {
                 'date_accidente': lambda *a: time.strftime('%Y-%m-%d %H:%M:%S'),
		         'cant':lambda obj, cr, uid, context: 1,
                }
medical_statistical_victim()




class medical_clinica_movil(osv.osv):
    #_name = "statiscal.victim"
    _inherit = "clinica.movil"
    _columns = {    'patient_id'        :fields.many2one('medical.patient', 'Paciente', required=False),
                    'name'              :fields.char('Nombres', size=64, required=False, readonly=False),
                    'lastname'          :fields.char('Apellidos', size=64, required=False, readonly=False),
                    'age'               :fields.char('Edad', size=64, required=False, readonly=False),
                    'date_inscription'  : fields.date ('Date'),                    
                    'cant'              : fields.float('Cant',),
                    'consultations'     : fields.many2one ('product.product', 'Consultation Service', domain=[('type', '=', "service")], help="Consultation Services", required=True),
                    'sector'            : fields.many2one ('medical.sector', 'Sector', required=False),
                  #  'sex'               : fields.related('patient_id','sex', type='selection', selection=[('m','Masculino'),('f','Femenino')], relation='medical.patient', string='Sexo', store=True),
                    #'age_range'         : fields.related('patient_id','sex', type='selection', selection=[('m','Masculino'),('f','Femenino')], relation='medical.patient', string='Sexo', store=True),
                    'sex':fields.selection([
                        ('m','Masculino'),
                        ('f','Femenino'),                       
                         ],    'Sexo', select=True),
                
                    'age_range':fields.selection([
                        ('1','Menor de un mes'),
                        ('2','1-11 meses'),
                        ('3','1-4 años'),
                        ('4','5-9 años'),
                        ('5','10-14 años'),
                        ('6','15-19 años'),
                        ('7','20-35 años'),
                        ('8','36-49 años'),
                        ('9','50-64 años'),
                        ('10','Mayor de 65 años'),                        
                         ],    'Rango', select=True),
                    'especialidad_id'   :fields.many2one('medical.speciality', 'Especialidad', required=False),
                    'day'               :fields.char('Dia', size=64, required=False, readonly=False), 
               }
    
    _defaults = {
                 'date_inscription' : lambda *a: time.strftime('%Y-%m-%d %H:%M:%S'),
                 'day'              : lambda *a: time.strftime('%Y-%m-%d'),
                 'cant'             : lambda obj, cr, uid, context: 1,
                }
    
    def onchange_date(self, cr, uid, ids, date, context=None):
        if not context:
            context={}
        result = {}
        if(date==False):
            result['value']={'day':''}
        else:            
            result['value']={'day':str(date)}
        return result
    
    def onchange_patient(self, cr, uid, ids, patient, date_inscription, context=None):       
        if not context:
            context={}            
        result = {}
        #now  = datetime.now()        
        edad = None
        
        result['value']={'age_range':False, 'sex':False}
        try:
            res = self.pool.get('medical.patient').browse(cr, uid, patient, context=context)           
            if not res:                
               result['value']={'age_range':False, 'sex':False}
            else:
                #hago una relacion entre la fecha actual y la fecha de nacimiento
                delta=relativedelta (datetime.strptime(date_inscription,'%Y-%m-%d'), datetime.strptime(res.dob,'%Y-%m-%d'))
                years = delta.years
                months = delta.months
                days = delta.days
                if(years<=0 and months<1):
                    edad = "1"
                if(years<=0 and months>=1):
                    edad = "2"
                if(years>=1 and years<=4):
                    edad = "3"
                if(years>=5 and years<=9):
                    edad = "4"
                if(years>=10 and years<=14):
                    edad = "5"
                if(years>=15 and years<=19):
                    edad = "6"
                if(years>=20 and years<=35):
                    edad = "7"
                if(years>=36 and years<=49):
                    edad = "8"
                if(years>=50 and years<=64):
                    edad = "9"
                if(years>=65):
                    edad = "10"
                #years_months_days = str(delta.years) +"y "+ str(delta.months) +"m "+ str(delta.days)+"d" + deceased
                result['value']={'age_range':edad, 'sex': str(res.sex)}
        except:
            result['value']={'age_range':False, 'sex': False}
            #raise osv.except_osv('Error', str(e))            
        return result
    
medical_clinica_movil()

   
