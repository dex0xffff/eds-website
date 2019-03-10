// Created by iWeb 3.0.1 local-build-20100330

setTransparentGifURL('../Media/transparent.gif');function applyEffects()
{var registry=IWCreateEffectRegistry();registry.registerEffects({stroke_1:new IWPhotoFrame([IWCreateImage('News_files/Creme_sidebar_frame_01.png'),IWCreateImage('News_files/Creme_sidebar_frame_02.png'),IWCreateImage('News_files/Creme_sidebar_frame_03.png'),IWCreateImage('News_files/Creme_sidebar_frame_06.png'),IWCreateImage('News_files/Creme_sidebar_frame_09.png'),IWCreateImage('News_files/Creme_sidebar_frame_08.png'),IWCreateImage('News_files/Creme_sidebar_frame_07.png'),IWCreateImage('News_files/Creme_sidebar_frame_04.png')],null,2,1.000000,0.000000,0.000000,0.000000,0.000000,10.000000,16.000000,10.000000,20.000000,523.000000,173.000000,523.000000,173.000000,null,null,null,0.100000),stroke_0:new IWPhotoFrame([IWCreateImage('News_files/Creme_frame3_01.png'),IWCreateImage('News_files/Creme_frame3_02.png'),IWCreateImage('News_files/Creme_frame3_03.png'),IWCreateImage('News_files/Creme_frame3_06.png'),IWCreateImage('News_files/Creme_frame3_09.png'),IWCreateImage('News_files/Creme_frame3_08.png'),IWCreateImage('News_files/Creme_frame3_07.png'),IWCreateImage('News_files/Creme_frame3_04.png')],null,0,1.000000,10.000000,10.000000,9.000000,10.000000,10.000000,10.000000,9.000000,10.000000,100.000000,150.000000,100.000000,150.000000,null,null,null,0.100000)});registry.applyEffects();}
function hostedOnDM()
{return false;}
function photocastSubscribe()
{photocastHelper("http://mysite.verizon.net/emsparks/Edward_Sparks_-_Singer_Songwriter_Musician_-/News/rss.xml");}
function onPageLoad()
{loadMozillaCSS('News_files/NewsMoz.css')
detectBrowser();adjustLineHeightIfTooBig('id1');adjustFontSizeIfTooBig('id1');adjustLineHeightIfTooBig('id2');adjustFontSizeIfTooBig('id2');adjustLineHeightIfTooBig('id3');adjustFontSizeIfTooBig('id3');adjustLineHeightIfTooBig('id4');adjustFontSizeIfTooBig('id4');Widget.onload();fixAllIEPNGs('../Media/transparent.gif');fixupAllIEPNGBGs();applyEffects()}
function onPageUnload()
{Widget.onunload();}
