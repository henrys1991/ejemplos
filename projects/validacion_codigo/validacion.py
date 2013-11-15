
# -*- encoding: utf-8 -*-
########################################################################
#                                                                       
# @authors: Pablo Vizhnay                                                                           
# Copyright (C) 2012  Geoinformatica                                 
#                                                                       
#This program is free software: you can redistribute it and/or modify   
#it under the terms of the GNU General Public License as published by   
#the Free Software Foundation, either version 3 of the License, or      
#(at your option) any later version.                                    
#
# This module is GPLv3 or newer and incompatible
# with OpenERP SA "AGPL + Private Use License"!
#                                                                       
#This program is distributed in the hope that it will be useful,        
#but WITHOUT ANY WARRANTY; without even the implied warranty of         
#MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the          
#GNU General Public License for more details.                           
#                                                                       
#You should have received a copy of the GNU General Public License      
#along with this program.  If not, see http://www.gnu.org/licenses.
########################################################################
from osv import osv
from osv import fields
import time
import datetime
from datetime import date
import pooler
import decimal_precision as dp
from tools.translate import _

class product_product(osv.osv):
    _inherit = 'product.product'    
    
    def _check_ean_key(self, cr, uid, ids, context=None):        
        return True
    
    _constraints = [(_check_ean_key, 'Error: Invalid ean code', ['ean13'])]
   
product_product()

