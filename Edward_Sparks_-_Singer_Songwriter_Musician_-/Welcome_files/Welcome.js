// Created by iWeb 3.0.1 local-build-20100728

setTransparentGifURL('Media/transparent.gif');function applyEffects()
{var registry=IWCreateEffectRegistry();registry.registerEffects({stroke_0:new IWPhotoFrame([IWCreateImage('Welcome_files/Creme_sidebar_frame_01.png'),IWCreateImage('Welcome_files/Creme_sidebar_frame_02.png'),IWCreateImage('Welcome_files/Creme_sidebar_frame_03.png'),IWCreateImage('Welcome_files/Creme_sidebar_frame_06.png'),IWCreateImage('Welcome_files/Creme_sidebar_frame_09.png'),IWCreateImage('Welcome_files/Creme_sidebar_frame_08.png'),IWCreateImage('Welcome_files/Creme_sidebar_frame_07.png'),IWCreateImage('Welcome_files/Creme_sidebar_frame_04.png')],null,2,1.000000,0.000000,0.000000,0.000000,0.000000,10.000000,16.000000,10.000000,20.000000,523.000000,173.000000,523.000000,173.000000,null,null,null,0.100000),shadow_0:new IWShadow({blurRadius:5,offset:new IWPoint(0.0000,0.0000),color:'#000000',opacity:0.370000})});registry.applyEffects();}
function hostedOnDM()
{return false;}
function onPageLoad()
{loadMozillaCSS('Welcome_files/WelcomeMoz.css')
adjustLineHeightIfTooBig('id2');adjustFontSizeIfTooBig('id2');adjustLineHeightIfTooBig('id3');adjustFontSizeIfTooBig('id3');Widget.onload();fixupAllIEPNGBGs();fixAllIEPNGs('Media/transparent.gif');fixupIECSS3Opacity('id1');applyEffects()}
function onPageUnload()
{Widget.onunload();}
