
<?php

class CvnRootBean{
var $CvnItemBean;//CvnItemBean
}
class CvnItemBean{
var $CvnAuthorBean;//CvnAuthorBean
var $CvnBoolean;//CvnBoolean
var $CvnCodeGroup;//CvnCodeGroup
var $CvnDateDayMonthYear;//CvnDateDayMonthYear
var $CvnDateMonthYear;//CvnDateMonthYear
var $CvnDateYear;//CvnDateYear
var $CvnDouble;//CvnDouble
var $CvnDuration;//CvnDuration
var $CvnEntityBean;//CvnEntityBean
var $CvnExternalPKBean;//CvnExternalPKBean
var $CvnFamilyNameBean;//CvnFamilyNameBean
var $CvnPageBean;//CvnPageBean
var $CvnPhoneBean;//CvnPhoneBean
var $CvnPhotoBean;//CvnPhotoBean
var $CvnString;//CvnString
var $CvnTitleBean;//CvnTitleBean
var $CvnVolumeBean;//CvnVolumeBean
}
class CvnBean{
var $Code;//string
}
class CvnAuthorBean{
var $CvnFamilyNameBean;//CvnFamilyNameBean
var $GivenName;//string
var $Signature;//string
var $SignatureOrder;//int
}
class CvnFamilyNameBean{
var $FirstFamilyName;//string
var $SecondFamilyName;//string
}
class CvnBoolean{
var $Value;//boolean
}
class CvnCodeGroup{
}
class CvnDateDayMonthYear{
var $Value;//dateTime
}
class CvnDateMonthYear{
var $Value;//dateTime
}
class CvnDateYear{
var $Value;//dateTime
}
class CvnDouble{
var $Value;//double
}
class CvnDuration{
var $Value;//string
}
class CvnEntityBean{
var $Id;//string
var $Name;//string
}
class CvnExternalPKBean{
var $Type;//string
var $Value;//string
}
class CvnPageBean{
var $FinalPage;//string
var $InitialPage;//string
}
class CvnPhoneBean{
var $Extension;//string
var $InternationalCode;//string
var $Number;//string
var $Type;//string
}
class CvnPhotoBean{
var $BytesInBase64;//string
var $MimeType;//string
}
class CvnString{
var $Value;//string
}
class CvnTitleBean{
var $Identification;//string
var $Name;//string
}
class CvnVolumeBean{
var $Number;//string
var $Volume;//string
}
class cvnPdf2Xml{
var $user;//string
var $passwd;//string
var $pdfBytes;//base64Binary
}
class cvnPdf2XmlResponse{
var $return;//cvnXmlResultBean
}
class cvnXmlResultBean{
var $cvnXml;//base64Binary
var $errorCode;//int
var $errorMessage;//string
var $numElementos;//int
}
class cvnPdf2CvnRootBean{
var $user;//string
var $passwd;//string
var $pdfBytes;//base64Binary
}
class cvnPdf2CvnRootBeanResponse{
var $return;//cvnRootResultBean
//var $result;
}
class cvnRootResultBean{
var $cvnRootBean;//CvnRootBean
var $errorCode;//int
var $errorMessage;//string
var $numElementos;//int
}
class cvnXml2CvnRootBean{
var $user;//string
var $passwd;//string
var $xmlBytes;//base64Binary
}
class cvnXml2CvnRootBeanResponse{
var $return;//cvnRootResultBean
}
class cvnXml2Xml{
var $user;//string
var $passwd;//string
var $xmlBytes;//base64Binary
}
class cvnXml2XmlResponse{
var $return;//cvnXmlResultBean
}
class WS_CVN 
 {
 var $soapClient;
 
private static $classmap = array('CvnRootBean'=>'CvnRootBean'
,'CvnItemBean'=>'CvnItemBean'
,'CvnBean'=>'CvnBean'
,'CvnAuthorBean'=>'CvnAuthorBean'
,'CvnFamilyNameBean'=>'CvnFamilyNameBean'
,'CvnBoolean'=>'CvnBoolean'
,'CvnCodeGroup'=>'CvnCodeGroup'
,'CvnDateDayMonthYear'=>'CvnDateDayMonthYear'
,'CvnDateMonthYear'=>'CvnDateMonthYear'
,'CvnDateYear'=>'CvnDateYear'
,'CvnDouble'=>'CvnDouble'
,'CvnDuration'=>'CvnDuration'
,'CvnEntityBean'=>'CvnEntityBean'
,'CvnExternalPKBean'=>'CvnExternalPKBean'
,'CvnPageBean'=>'CvnPageBean'
,'CvnPhoneBean'=>'CvnPhoneBean'
,'CvnPhotoBean'=>'CvnPhotoBean'
,'CvnString'=>'CvnString'
,'CvnTitleBean'=>'CvnTitleBean'
,'CvnVolumeBean'=>'CvnVolumeBean'
,'cvnPdf2Xml'=>'cvnPdf2Xml'
,'cvnPdf2XmlResponse'=>'cvnPdf2XmlResponse'
,'cvnXmlResultBean'=>'cvnXmlResultBean'
,'cvnPdf2CvnRootBean'=>'cvnPdf2CvnRootBean'
,'cvnPdf2CvnRootBeanResponse'=>'cvnPdf2CvnRootBeanResponse'
,'cvnRootResultBean'=>'cvnRootResultBean'
,'cvnXml2CvnRootBean'=>'cvnXml2CvnRootBean'
,'cvnXml2CvnRootBeanResponse'=>'cvnXml2CvnRootBeanResponse'
,'cvnXml2Xml'=>'cvnXml2Xml'
,'cvnXml2XmlResponse'=>'cvnXml2XmlResponse'

);

//'https://www.cvnet.es/cvn2RootBean/services/Cvn2RootBean?wsdl'

 function __construct($url='https://integraciones.cvnet.es/cvn2RootBean_v1_3/services/Cvn2RootBean?wsdl')
 {
  $this->soapClient = new SoapClient($url,array("classmap"=>self::$classmap,"trace" => true,"exceptions" => true));
 }
 
function cvnPdf2Xml($cvnPdf2Xml)
{

$cvnPdf2XmlResponse = $this->soapClient->cvnPdf2Xml($cvnPdf2Xml);
return $cvnPdf2XmlResponse;

}
function cvnXml2CvnRootBean($cvnXml2CvnRootBean)
{

$cvnXml2CvnRootBeanResponse = $this->soapClient->cvnXml2CvnRootBean($cvnXml2CvnRootBean);
return $cvnXml2CvnRootBeanResponse;

}
function cvnPdf2CvnRootBean($cvnPdf2CvnRootBean)
{

$cvnPdf2CvnRootBeanResponse = $this->soapClient->cvnPdf2CvnRootBean($cvnPdf2CvnRootBean);
return $cvnPdf2CvnRootBeanResponse;

}
function cvnXml2Xml($cvnXml2Xml)
{

$cvnXml2XmlResponse = $this->soapClient->cvnXml2Xml($cvnXml2Xml);
return $cvnXml2XmlResponse;

}}


?>