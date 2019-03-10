// Created by iWeb 3.0.1 local-build-20100728

function writeMovie1()
{detectBrowser();if(windowsInternetExplorer)
{document.write('<object id="id2" classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" codebase="http://www.apple.com/qtactivex/qtplugin.cab" width="108" height="16" style="height: 16px; left: 31px; position: absolute; top: 125px; width: 108px; z-index: 1; "><param name="src" value="Media/Meet%20me%20half%20way.mp3" /><param name="controller" value="true" /><param name="autoplay" value="false" /><param name="scale" value="tofit" /><param name="volume" value="100" /><param name="loop" value="false" /></object>');}
else if(isiPhone)
{document.write('<object id="id2" type="video/quicktime" width="108" height="16" style="height: 16px; left: 31px; position: absolute; top: 125px; width: 108px; z-index: 1; "><param name="src" value="Media/Meet%20me%20half%20way.mp3"/><param name="controller" value="true"/><param name="scale" value="tofit"/></object>');}
else
{document.write('<object id="id2" type="video/quicktime" width="108" height="16" data="Media/Meet%20me%20half%20way.mp3" style="height: 16px; left: 31px; position: absolute; top: 125px; width: 108px; z-index: 1; "><param name="src" value="Media/Meet%20me%20half%20way.mp3"/><param name="controller" value="true"/><param name="autoplay" value="false"/><param name="scale" value="tofit"/><param name="volume" value="100"/><param name="loop" value="false"/></object>');}}
setTransparentGifURL('Media/transparent.gif');function hostedOnDM()
{return false;}
function onPageLoad()
{loadMozillaCSS('Blank_files/BlankMoz.css')
Widget.onload();fixAllIEPNGs('Media/transparent.gif');fixupAllIEPNGBGs();fixupIECSS3Opacity('id1');performPostEffectsFixups()}
function onPageUnload()
{Widget.onunload();}
