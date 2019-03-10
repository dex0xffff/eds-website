// Created by iWeb 3.0.1 local-build-20100330

setTransparentGifURL('Media/transparent.gif');function applyEffects()
{var registry=IWCreateEffectRegistry();registry.registerEffects({stroke_1:new IWPhotoFrame([IWCreateImage('About_Me_files/Creme_sidebar_frame_01.png'),IWCreateImage('About_Me_files/Creme_sidebar_frame_02.png'),IWCreateImage('About_Me_files/Creme_sidebar_frame_03.png'),IWCreateImage('About_Me_files/Creme_sidebar_frame_06.png'),IWCreateImage('About_Me_files/Creme_sidebar_frame_09.png'),IWCreateImage('About_Me_files/Creme_sidebar_frame_08.png'),IWCreateImage('About_Me_files/Creme_sidebar_frame_07.png'),IWCreateImage('About_Me_files/Creme_sidebar_frame_04.png')],null,2,1.000000,0.000000,0.000000,0.000000,0.000000,10.000000,16.000000,10.000000,20.000000,523.000000,173.000000,523.000000,173.000000,null,null,null,0.100000),stroke_0:new IWPhotoFrame([IWCreateImage('About_Me_files/Creme_frame3_01.png'),IWCreateImage('About_Me_files/Creme_frame3_02.png'),IWCreateImage('About_Me_files/Creme_frame3_03.png'),IWCreateImage('About_Me_files/Creme_frame3_06.png'),IWCreateImage('About_Me_files/Creme_frame3_09.png'),IWCreateImage('About_Me_files/Creme_frame3_08.png'),IWCreateImage('About_Me_files/Creme_frame3_07.png'),IWCreateImage('About_Me_files/Creme_frame3_04.png')],null,0,1.000000,10.000000,10.000000,9.000000,10.000000,10.000000,10.000000,9.000000,10.000000,100.000000,150.000000,100.000000,150.000000,null,null,null,0.100000)});registry.applyEffects();}
function hostedOnDM()
{return false;}
function onPageLoad()
{loadMozillaCSS('About_Me_files/About_MeMoz.css')
adjustLineHeightIfTooBig('id1');adjustFontSizeIfTooBig('id1');adjustLineHeightIfTooBig('id2');adjustFontSizeIfTooBig('id2');Widget.onload();fixupAllIEPNGBGs();fixAllIEPNGs('Media/transparent.gif');applyEffects()}
function onPageUnload()
{Widget.onunload();}
