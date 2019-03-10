<?php
require_once( dirname(__FILE__).'/form.lib.php' );

define( 'PHPFMG_USER', "joshuasparks13@gmail.com" ); // must be a email address. for sending password to you.
define( 'PHPFMG_PW', "20101008-b8a5" );

?>
<?php
/**
 * Copyright (C) : http://www.formmail-maker.com
*/

# main
# ------------------------------------------------------
error_reporting( E_ERROR ) ;
phpfmg_admin_main();
# ------------------------------------------------------




function phpfmg_admin_main(){
    $mod  = isset($_REQUEST['mod'])  ? $_REQUEST['mod']  : '';
    $func = isset($_REQUEST['func']) ? $_REQUEST['func'] : '';
    $function = "phpfmg_{$mod}_{$func}";
    if( !function_exists($function) ){
        phpfmg_admin_default();
        exit;
    };

    // no login required modules
    $public_modules   = false !== strpos('|captcha|', "|{$mod}|");
    $public_functions = false !== strpos('|phpfmg_mail_password||phpfmg_filman_download||phpfmg_image_processing||phpfmg_dd_lookup|', "|{$function}|") ;   
    if( $public_modules || $public_functions ) { 
        $function();
        exit;
    };
    
    return phpfmg_user_isLogin() ? $function() : phpfmg_admin_default();
}

function phpfmg_admin_default(){
    if( phpfmg_user_login() ){
        phpfmg_admin_panel();
    };
}



function phpfmg_admin_panel()
{    
    phpfmg_admin_header();
    phpfmg_writable_check();
?>    
<table cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td valign=top style="padding-left:280px;">

<style type="text/css">
    .fmg_title{
        font-size: 16px;
        font-weight: bold;
        padding: 10px;
    }
    
    .fmg_sep{
        width:32px;
    }
    
    .fmg_text{
        line-height: 150%;
        vertical-align: top;
        padding-left:28px;
    }

</style>


<div class="fmg_title">
    1. Email Traffics
</div>
<div class="fmg_text">
    <a href="admin.php?mod=log&func=view&file=1">view</a> &nbsp;&nbsp;
    <a href="admin.php?mod=log&func=download&file=1">download</a>
</div>


<div class="fmg_title">
    2. Form Data
</div>
<div class="fmg_text">
    <a href="admin.php?mod=log&func=view&file=2">view</a> &nbsp;&nbsp;
    <a href="admin.php?mod=log&func=download&file=2">download</a>
</div>


<div class="fmg_title">
    3. Form Generator
</div>
<div class="fmg_text">
    <a href="http://www.formmail-maker.com/generator.php" onclick="document.frmFormMail.submit(); return false;" title="<?php echo htmlspecialchars(PHPFMG_SUBJECT);?>">Edit Form</a> &nbsp;&nbsp;
    <a href="http://www.formmail-maker.com/generator.php" >New Form</a>
</div>
    <form name="frmFormMail" action='http://www.formmail-maker.com/generator.php' method='post' enctype='multipart/form-data'>
    <input type="hidden" name="uuid" value="<?php echo PHPFMG_ID; ?>">
    <input type="hidden" name="external_ini" value="<?php echo phpfmg_formini(); ?>">
    </form>

		</td>
	</tr>
</table>

<?php
    phpfmg_admin_footer();
}



function phpfmg_admin_header( $title = '' ){
    header( "Content-Type: text/html; charset=" . PHPFMG_CHARSET );
?>
<html>
<head>
    <title><?php echo '' == $title ? '' : $title . ' | ' ; ?>PHP FormMail Admin Panel </title>
    <meta name="keywords" content="PHP FormMail Generator, PHP HTML form, send html email with attachment, PHP web form,  Free Form, Form Builder, Form Creator, phpFormMailGen, Customized Web Forms, phpFormMailGenerator,formmail.php, formmail.pl, formMail Generator, ASP Formmail, ASP form, PHP Form, Generator, phpFormGen, phpFormGenerator, anti-spam, web hosting">
    <meta name="description" content="PHP formMail Generator - A tool to ceate ready-to-use web forms in a flash. Validating form with CAPTCHA security image, send html email with attachments, send auto response email copy, log email traffics, save and download form data in Excel. ">
    <meta name="generator" content="PHP Mail Form Generator, phpfmg.sourceforge.net">

    <style type='text/css'>
    body, td, label, div, span{
        font-family : Verdana, Arial, Helvetica, sans-serif;
        font-size : 12px;
    }
    </style>
</head>
<body  marginheight="0" marginwidth="0" leftmargin="0" topmargin="0">

<table cellspacing=0 cellpadding=0 border=0 width="100%">
    <td nowrap align=center style="background-color:#024e7b;padding:10px;font-size:18px;color:#ffffff;font-weight:bold;width:250px;" >
        Form Admin Panel
    </td>
    <td style="padding-left:30px;background-color:#86BC1B;width:100%;font-weight:bold;" >
        &nbsp;
<?php
    if( phpfmg_user_isLogin() ){
        echo '<a href="admin.php" style="color:#ffffff;">Main Menu</a> &nbsp;&nbsp;' ;
        echo '<a href="admin.php?mod=user&func=logout" style="color:#ffffff;">Logout</a>' ;
    }; 
?>
    </td>
</table>

<div style="padding-top:28px;">

<?php
    
}


function phpfmg_admin_footer(){
?>

</div>

<div style="color:#cccccc;text-decoration:none;padding:18px;font-weight:bold;">
	:: <a href="http://phpfmg.sourceforge.net" target="_blank" title="Free Mailform Maker: Create read-to-use Web Forms in a flash. Including validating form with CAPTCHA security image, send html email with attachments, send auto response email copy, log email traffics, save and download form data in Excel. " style="color:#cccccc;font-weight:bold;text-decoration:none;">PHP FormMail Generator</a> ::
</div>

</body>
</html>
<?php
}


function phpfmg_image_processing(){
    $img = new phpfmgImage();
    $img->out_processing_gif();
}


# phpfmg module : captcha
# ------------------------------------------------------
function phpfmg_captcha_get(){
    $img = new phpfmgImage();
    $img->out();
    $_SESSION[PHPFMG_ID.'fmgCaptchCode'] = $img->text ;
}



function phpfmg_captcha_generate_images(){
    for( $i = 0; $i < 50; $i ++ ){
        $file = "$i.png";
        $img = new phpfmgImage();
        $img->out($file);
        $data = base64_encode( file_get_contents($file) );
        echo "'{$img->text}' => '{$data}',\n" ;
        unlink( $file );
    };
}


function phpfmg_dd_lookup(){
    $paraOk = ( isset($_REQUEST['n']) && isset($_REQUEST['lookup']) && isset($_REQUEST['field_name']) );
    if( !$paraOk )
        return;
        
    $base64 = phpfmg_dependent_dropdown_data();
    $data = @unserialize( base64_decode($base64) );
    if( !is_array($data) ){
        return ;
    };
    
    
    foreach( $data as $field ){
        if( $field['name'] == $_REQUEST['field_name'] ){
            $nColumn = intval($_REQUEST['n']);
            $lookup  = $_REQUEST['lookup']; // $lookup is an array
            $dd      = new DependantDropdown(); 
            echo $dd->lookupFieldColumn( $field, $nColumn, $lookup );
            return;
        };
    };
    
    return;
}


function phpfmg_filman_download(){
    if( !isset($_REQUEST['filelink']) )
        return ;
        
    $info =  @unserialize(base64_decode($_REQUEST['filelink']));
    if( !isset($info['recordID']) ){
        return ;
    };
    
    $file = PHPFMG_SAVE_ATTACHMENTS_DIR . $info['recordID'] . '-' . $info['filename'];
    phpfmg_util_download( $file, $info['filename'] );
}


class phpfmgDataManager
{
    var $dataFile = '';
    var $columns = '';
    var $records = '';
    
    function phpfmgDataManager(){
        $this->dataFile = PHPFMG_SAVE_FILE; 
    }
    
    function parseFile(){
        $fp = @fopen($this->dataFile, 'rb');
        if( !$fp ) return false;
        
        $i = 0 ;
        $phpExitLine = 1; // first line is php code
        $colsLine = 2 ; // second line is column headers
        $this->columns = array();
        $this->records = array();
        $sep = chr(0x09);
        while( !feof($fp) ) { 
            $line = fgets($fp);
            $line = trim($line);
            if( empty($line) ) continue;
            $line = $this->line2display($line);
            $i ++ ;
            switch( $i ){
                case $phpExitLine:
                    continue;
                    break;
                case $colsLine :
                    $this->columns = explode($sep,$line);
                    break;
                default:
                    $this->records[] = explode( $sep, phpfmg_data2record( $line, false ) );
            };
        }; 
        fclose ($fp);
    }
    
    function displayRecords(){
        $this->parseFile();
        echo "<table border=1 style='width=95%;border-collapse: collapse;border-color:#cccccc;' >";
        echo "<tr><td>&nbsp;</td><td><b>" . join( "</b></td><td>&nbsp;<b>", $this->columns ) . "</b></td></tr>\n";
        $i = 1;
        foreach( $this->records as $r ){
            echo "<tr><td align=right>{$i}&nbsp;</td><td>" . join( "</td><td>&nbsp;", $r ) . "</td></tr>\n";
            $i++;
        };
        echo "</table>\n";
    }
    
    function line2display( $line ){
        $line = str_replace( array('"' . chr(0x09) . '"', '""'),  array(chr(0x09),'"'),  $line );
        $line = substr( $line, 1, -1 ); // chop first " and last "
        return $line;
    }
    
}
# end of class



# ------------------------------------------------------
class phpfmgImage
{
    var $im = null;
    var $width = 73 ;
    var $height = 33 ;
    var $text = '' ; 
    var $line_distance = 8;
    var $text_len = 4 ;

    function phpfmgImage( $text = '', $len = 4 ){
        $this->text_len = $len ;
        $this->text = '' == $text ? $this->uniqid( $this->text_len ) : $text ;
        $this->text = strtoupper( substr( $this->text, 0, $this->text_len ) );
    }
    
    function create(){
        $this->im = imagecreate( $this->width, $this->height );
        $bgcolor   = imagecolorallocate($this->im, 255, 255, 255);
        $textcolor = imagecolorallocate($this->im, 0, 0, 0);
        $this->drawLines();
        imagestring($this->im, 5, 20, 9, $this->text, $textcolor);
    }
    
    function drawLines(){
        $linecolor = imagecolorallocate($this->im, 210, 210, 210);
    
        //vertical lines
        for($x = 0; $x < $this->width; $x += $this->line_distance) {
          imageline($this->im, $x, 0, $x, $this->height, $linecolor);
        };
    
        //horizontal lines
        for($y = 0; $y < $this->height; $y += $this->line_distance) {
          imageline($this->im, 0, $y, $this->width, $y, $linecolor);
        };
    }
    
    function out( $filename = '' ){
        if( function_exists('imageline') ){
            $this->create();
            if( '' == $filename ) header("Content-type: image/png");
            ( '' == $filename ) ? imagepng( $this->im ) : imagepng( $this->im, $filename );
            imagedestroy( $this->im ); 
        }else{
            $this->out_predefined_image(); 
        };
    }

    function uniqid( $len = 0 ){
        $md5 = md5( uniqid(rand()) );
        return $len > 0 ? substr($md5,0,$len) : $md5 ;
    }
    
    function out_predefined_image(){
        header("Content-type: image/png");
        $data = $this->getImage(); 
        echo base64_decode($data);
    }
    
    // predefined random images
    function getImage(){
        $images = array(
			'1DFC' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWklEQVR4nGNYhQEaGAYTpIn7GB1EQ1hDA6YGIImxOoi0sjYwBIggiYk6iDS6AlWzoOiFiCG7b2XWtJWpoSuzkN2Hpo6gGJodmG4JAbq5gQHFzQMVflSEWNwHAI3syHvt/+7zAAAAAElFTkSuQmCC',
			'EAE8' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXklEQVR4nGNYhQEaGAYTpIn7QkMYAlhDHaY6IIkFNDCGsDYwBASgiLG2sjYwOoigiIk0uiLUgZ0UGjVtZWroqqlZSO5DUwcVEw11xWoeXjugbgaKobl5oMKPihCL+wChLs2gv9wwAwAAAABJRU5ErkJggg==',
			'12A7' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdklEQVR4nGNYhQEaGAYTpIn7GB0YQximMIaGIImxOrC2MoQyNIggiYk6iDQ6OjqgiDE6MDS6NgQAIcJ9K7NWLV26KgpIIdwHVDeFtSGgFdVehgDW0IApaG5xAKoLQBVjbWBtCHRAFhMNEQ11RRMbqPCjIsTiPgDhTsmGgnP3/AAAAABJRU5ErkJggg==',
			'3A07' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7RAMYAhimMIaGIIkFTGEMYQhlaBBBVtnK2sro6IAqNkWk0bUhAAgR7lsZNW1l6qqolVnI7oOoa0WxuVU0FCg2BVVMpNHR0SGAAcUtIo0OoYwOqG4Gik1BFRuo8KMixOI+ACWpzDaYRr4CAAAAAElFTkSuQmCC',
			'3703' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7RANEQx2mMIQ6IIkFTGFodAhldAhAVtnK0Ojo6NAggiw2haGVtSGgIQDJfSujVk1buipqaRay+6YwBCCpg5rH6AASQzEPaBojmh0BU4A8NLeIBoiAzERx80CFHxUhFvcBALXTzHv2pLnXAAAAAElFTkSuQmCC',
			'6F4E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7WANEQx0aHUMDkMREpog0MLQ6OiCrC2gBik1FE2sAigXCxcBOioyaGrYyMzM0C8l9IUDzWBvR9LYCxUIDMcQY0NSB3YImxhoAFkNx80CFHxUhFvcBABd8y09m0zpaAAAAAElFTkSuQmCC',
			'D79D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7QgNEQx1CGUMdkMQCpjA0Ojo6OgQgi7UyNLo2BDqIoIq1siLEwE6KWrpq2srMyKxpSO4DqgtgCEHXy+jAgGEeawMjutgUkQZGNLeEBgBVoLl5oMKPihCL+wAB78yzewhEUQAAAABJRU5ErkJggg==',
			'ADA8' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7GB1EQximMEx1QBJjDRBpZQhlCAhAEhOZItLo6OjoIIIkFtAq0ujaEABTB3ZS1NJpK1NXRU3NQnIfmjowDA0FioUGYjEPQ6yVFU1vQKtoCFAMxc0DFX5UhFjcBwCLbM6W/trTrAAAAABJRU5ErkJggg==',
			'170A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7GB1EQx2mMLQii7E6MDQ6hDJMdUASEwWKOTo6BASg6GVoZW0IdBBBct/KrFXTlq6KzJqG5D6gugAkdVAxRgegWGgIihhrAyPQElR1QF4oI4qYaAiQNwVVbKDCj4oQi/sAvrHIYJ4/MQIAAAAASUVORK5CYII=',
			'48F2' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpI37pjCGsIYGTHVAFgthbWVtYAgIQBJjDBFpdG1gdBBBEmOdAlbXIILkvmnTVoYtDV21KgrJfQEQdY3IdoSGgsxjaEV1C1hsCqoYxC0Ybm5gDA0ZDOFHPYjFfQBilMuTRyxtdgAAAABJRU5ErkJggg==',
			'3C46' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7RAMYQxkaHaY6IIkFTGFtdGh1CAhAVtkq0uAw1dFBAFlsikgDQ6CjA7L7VkZNW7UyMzM1C9l9QHWsjY4Y5rGGBjqIoNvR6IgiBnZLI6pbsLl5oMKPihCL+wBVvM0jBp9HxQAAAABJRU5ErkJggg==',
			'03CB' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7GB1YQxhCHUMdkMRYA0RaGR0CHQKQxESmMDS6Ngg6iCCJBbQytLICTQhAcl/U0lVhS1etDM1Cch+aOpgY0DxGFPOw2YHNLdjcPFDhR0WIxX0ANo3KoZuovRQAAAAASUVORK5CYII=',
			'0617' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7GB0YQximMIaGIImxBrC2MoQwNIggiYlMEWlkRBMLaAXypgBpJPdFLZ0WtmraqpVZSO4LaBVtBaprZUDV2+gwBaQb1Q6gWAADulumMDqgu5kx1BFFbKDCj4oQi/sAUUnKnZ16gAAAAAAASUVORK5CYII=',
			'E704' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7QkNEQx2mMDQEIIkB2Y0OoQyN6GKOjg6taGKtrA0BUwKQ3BcatWra0lVRUVFI7gPKB7A2BDqg6mV0AIqFhqCIsTYwOjqguUWkgSEU1X2hIUAxNDcPVPhREWJxHwCxZ87itt8I+QAAAABJRU5ErkJggg==',
			'0A27' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdUlEQVR4nGNYhQEaGAYTpIn7GB0YAhhCGUNDkMRYAxhDGB0dGkSQxESmsLayNgSgiAW0ijQ6AMUCkNwXtXTayiwQRHIfWF0rQysDil7RUIcpDFMYUOwAqgsAugfFLSKNjg5AV6K4WaTRNTQQRWygwo+KEIv7AEXqy3M+1MBKAAAAAElFTkSuQmCC',
			'5FD5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7QkNEQ11DGUMDkMQCGkQaWBsdHRjQxRoCUcQCA8Birg5I7gubNjVs6arIqChk97WC1IFMQNKNRSygFWIHspjIFJBbHAKQ3ccKsjeUYarDIAg/KkIs7gMAqSjMl2cBgJ0AAAAASUVORK5CYII=',
			'2709' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdUlEQVR4nM2QwQ2AIAxFy4ENcJ92g3qoB6bBAxsAG3hhSjHGpKJHjfbfXvrTl0K9TIA/5RU/y4NggoyKuQQzCjArxhFmIkKn2xGiDePBdqdSy1K9n7QfA9vAWXcNGmwsaGZbDOHphts2OheRxjrnr/73YG78VhYwyzhV1n7qAAAAAElFTkSuQmCC',
			'ED85' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7QkNEQxhCGUMDkMQCGkRaGR0dHRhQxRpdGwIxxBwdHV0dkNwXGjVtZVboyqgoJPdB1Dk0iGCYF4BFLNBBBMMtDgHI7oO4mWGqwyAIPypCLO4DAFv6zVWpICh2AAAAAElFTkSuQmCC',
			'84FF' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7WAMYWllDA0NDkMREpjBMZW1gdEBWF9DKEIouJjKF0RVJDOykpVFLly4NXRmaheQ+kSkirZjmiYa6YtqBoQ7oFgwxsJvRxAYq/KgIsbgPABSgyMr5Q4LRAAAAAElFTkSuQmCC',
			'605B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7WAMYAlhDHUMdkMREpjCGsDYwOgQgiQW0sLaCxESQxRpEGl2nwtWBnRQZNW1lamZmaBaS+0KmiDQ6NASimtcKEUMxrxVkB6oYyC2Mjo4oekFuZghlRHHzQIUfFSEW9wEA8mnLOXeaBXUAAAAASUVORK5CYII=',
			'E5BC' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpIn7QkNEQ1lDGaYGIIkFNIg0sDY6BIigizUEOrCgioWwNjo6ILsvNGrq0qWhK7OQ3Qc0u9EVoQ4hBjQPVUwELIZqB2srultCQxhD0N08UOFHRYjFfQB8R81HStKaHwAAAABJRU5ErkJggg==',
			'A2DA' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdklEQVR4nGNYhQEaGAYTpIn7GB0YQ1hDGVqRxVgDWFtZGx2mOiCJiUwRaXRtCAgIQBILaGUAigU6iCC5L2rpqqVLV0VmTUNyH1DdFFaEOjAMDWUIAIqFhqCYx+iAri6glbWBtdERTUw01DWUEUVsoMKPihCL+wDh5MzRoY7TxQAAAABJRU5ErkJggg==',
			'5017' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7QkMYAhimMIaGIIkFNDCGMIQwNIigiLG2MqKJBQaINDpMAckh3Bc2bdrKrGmrVmYhu68VrK4VxWaI2BRksYBW1lagSACymMgUoK1TGB2QxVgDGAIYQx1RxAYq/KgIsbgPAC+ZyxqfDh3DAAAAAElFTkSuQmCC',
			'E095' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7QkMYAhhCGUMDkMQCGhhDGB0dHRhQxFhbWRsC0cREGl0bAl0dkNwXGjVtZWZmZFQUkvtA6hxCgCSaXocGdDHWVkagHSIYbnEIQHYfxM0MUx0GQfhREWJxHwD4usw0K0fCOQAAAABJRU5ErkJggg==',
			'77D9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7QkNFQ11DGaY6IIu2MjS6NjoEBKCLNQQ6iCCLTWFoZUWIQdwUtWra0lVRUWFI7mN0YAhgbQiYiqyXFSgKFGtAFhMBigLFUOwAqWBFcwtYDN3NAxR+VIRY3AcAvLXMp1w1J0QAAAAASUVORK5CYII=',
			'9850' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nGNYhQEaGAYTpIn7WAMYQ1hDHVqRxUSmsLayNjBMdUASC2gVaXRtYAgIQBEDqpvK6CCC5L5pU1eGLc3MzJqG5D5WV1ag+YEwdRAINM8BTUwAbEcAih0gtzA6OqC4BeRmhlAGFDcPVPhREWJxHwCy+MvCvjrQdwAAAABJRU5ErkJggg==',
			'38C0' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXUlEQVR4nGNYhQEaGAYTpIn7RAMYQxhCHVqRxQKmsLYyOgRMdUBW2SrS6NogEBCALAZUx9rA6CCC5L6VUSvDlq5amTUN2X2o6pDMwyaGagc2t2Bz80CFHxUhFvcBALm0y9czO/s6AAAAAElFTkSuQmCC',
			'A9DA' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7GB0YQ1hDGVqRxVgDWFtZGx2mOiCJiUwRaXRtCAgIQBILaAWJBTqIILkvaunSpamrIrOmIbkvoJUxEEkdGIaGMoD0hoagmMfSiK4uoBXkFkc0MZCbGVHEBir8qAixuA8AZdzNQ3fZuWcAAAAASUVORK5CYII=',
			'8289' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nGNYhQEaGAYTpIn7WAMYQxhCGaY6IImJTGFtZXR0CAhAEgtoFWl0bQh0EEFRx9Do6OgIEwM7aWnUqqWrQldFhSG5D6huCtC8qSIo5jEEsDYENKCKMToAxdDsYG1AdwtrgGioA5qbByr8qAixuA8AtdbL2r1Nh2kAAAAASUVORK5CYII=',
			'354F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7RANEQxkaHUNDkMQCpog0MLQ6OqCobAWKTUUTmyISwhAIFwM7aWXU1KUrMzNDs5DdN4Wh0bUR3TygWGgguh2NDmjqAqawAlWiiokGMIagiw1U+FERYnEfADyIyozpVvJ3AAAAAElFTkSuQmCC',
			'292A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAe0lEQVR4nGNYhQEaGAYTpIn7WAMYQxhCGVqRxUSmsLYyOjpMdUASC2gVaXRtCAgIQNYNFHNoCHQQQXbftKVLs1ZmZk1Ddl8AY6BDKyNMHRgyOjA0OkxhDA1BdksDS6NDAKo6kQagWxxQxUJDGUNYQwNRxAYq/KgIsbgPAImiypn2K3fxAAAAAElFTkSuQmCC',
			'AE41' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7GB1EQxkaHVqRxVgDRBoYWh2mIouJTAGKTXUIRRYLaAWKBcL1gp0UtXRq2MrMrKXI7gOpY0WzIzQUKBYa0IphHpo67GJgN4cGDILwoyLE4j4AD/XNPeuFV9QAAAAASUVORK5CYII=',
			'9EDB' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWklEQVR4nGNYhQEaGAYTpIn7WANEQ1lDGUMdkMREpog0sDY6OgQgiQW0AsUaAh1EsIgFILlv2tSpYUtXRYZmIbmP1RVFHQRiMU8Aixg2t2Bz80CFHxUhFvcBAHOzy4iGMwdSAAAAAElFTkSuQmCC',
			'4506' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpI37poiGMkxhmOqALBYi0sAQyhAQgCTGCBRjdHR0EEASY50iEsLaEOiA7L5p06YuXboqMjULyX0BUxgaXRsCUcwLDQWLOYiguEWk0RFoB6oYayu6WximMIZguHmgwo96EIv7ADGKy5RbazTWAAAAAElFTkSuQmCC',
			'DD44' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7QgNEQxgaHRoCkMQCpoi0MrQ6NKKItYo0Okx1aMUQC3SYEoDkvqil01ZmZmZFRSG5D6TOtdHRAV2va2hgaAi6edjcgiaGzc0DFX5UhFjcBwDSXtGFW4k8HQAAAABJRU5ErkJggg==',
			'F809' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7QkMZQximMEx1QBILaGBtZQhlCAhAERNpdHR0dBBBU8faEAgTAzspNGpl2NJVUVFhSO6DqAuYKoJmniuIxLDDAcMOTLdgunmgwo+KEIv7AGQpzUdrJwN6AAAAAElFTkSuQmCC',
			'19F4' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7GB0YQ1hDAxoCkMRYHVhbWRsYGpHFRB1EGl0bGFoDUPSCxaYEILlvZdbSpamhq6KikNwHtCPQFUii6mUA6mUMDUERYwGZ14CqDuwWFDHREKCb0cQGKvyoCLG4DwA6+Mqgp2Fw5AAAAABJRU5ErkJggg==',
			'C2CF' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7WEMYQxhCHUNDkMREWllbGR0CHZDVBTSKNLo2CKKKNTAAxRhhYmAnRa1atXTpqpWhWUjuA6qbwopQBxMLwBBrZHRgRbMD6JYGdLewhoiGOoQ6oogNVPhREWJxHwBt9cnLpVsWZgAAAABJRU5ErkJggg==',
			'4C3B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpI37pjCGgqADslgIa6Nro6NDAJIYY4hIg0NDoIMIkhjrFCAPoQ7spGnTpq1aNXVlaBaS+wJQ1YFhaCjIJFTzGKZg2sEwBdMtWN08UOFHPYjFfQCyQsyz8m5T/AAAAABJRU5ErkJggg==',
			'F1CD' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWUlEQVR4nGNYhQEaGAYTpIn7QkMZAhhCHUMdkMQCGhgDGB0CHQJQxFgDWBsEHURQxBiAYowwMbCTQqNWRS1dtTJrGpL70NQREMO0A4tbQtHdPFDhR0WIxX0AY1LJ/Mzr2PUAAAAASUVORK5CYII=',
			'48BB' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXUlEQVR4nGNYhQEaGAYTpI37pjCGsIYyhjogi4WwtrI2OjoEIIkxhog0ujYEOoggibFOQVEHdtK0aSvDloauDM1Ccl/AFEzzQkMxzWOYgk0MUy9WNw9U+FEPYnEfAMhIzANI6bO+AAAAAElFTkSuQmCC',
			'68EF' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAV0lEQVR4nGNYhQEaGAYTpIn7WAMYQ1hDHUNDkMREprC2sjYwOiCrC2gRaXRFF2tAUQd2UmTUyrCloStDs5DcF4LNvFYs5mERw+YWqJtRxAYq/KgIsbgPANNxyWmuolwIAAAAAElFTkSuQmCC',
			'1167' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7GB0YAhhCGUNDkMRYHRgDGB0dGkSQxEQdWANYG1DFQHpZgXQAkvtWZq2KWjoVRCHcB1bn6NCKbi9rQ8AULGIB6GKMjo4OyGKiIayhQDejiA1U+FERYnEfADuIxnfp7KZVAAAAAElFTkSuQmCC',
			'4089' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpI37pjAEMIQyTHVAFgthDGF0dAgIQBJjDGFtZW0IdBBBEmOdItLo6OgIEwM7adq0aSuzQldFhSG5LwCszmEqst7QUJFG14aABhEUt4DsCHBAFcN0C1Y3D1T4UQ9icR8An0DLIqhg6vwAAAAASUVORK5CYII=',
			'4D28' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nGNYhQEaGAYTpI37poiGMIQyTHVAFgsRaWV0dAgIQBJjDBFpdG0IdBBBEmOdItLo0BAAUwd20rRp01ZmrcyamoXkvgCQulYGFPNCQ4FiUxhRzGMAqQvAEGtldEDVC3Iza2gAqpsHKvyoB7G4DwB+ksx4xCG0WgAAAABJRU5ErkJggg==',
			'F58A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7QkNFQxlCGVqRxQIaRBoYHR2mOqCJsTYEBASgioUwOjo6iCC5LzRq6tJVoSuzpiG5D6in0RGhDi7m2hAYGoJqHkgMTR1rKyOGXsYQhlBGFLGBCj8qQizuAwDpAMy0SnQuawAAAABJRU5ErkJggg==',
			'8A74' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7WAMYAlhDAxoCkMREpjCGMDQENCKLBbSytoJIVHUijQ6NDlMCkNy3NGrayqylq6KikNwHVjeF0QHVPNFQhwDG0BAUMZFGRweGBnQ7XBtQxVgDMMUGKvyoCLG4DwCYFc8AMYOxPQAAAABJRU5ErkJggg==',
			'9566' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nGNYhQEaGAYTpIn7WANEQxlCGaY6IImJTBFpYHR0CAhAEgtoFWlgbXB0EEAVC2FtYHRAdt+0qVOXLp26MjULyX2srgyNro6OKOYxtALFGgIdRJDEBFpFMMREprC2oruFNYAxBN3NAxV+VIRY3AcAV8HLie/B8mUAAAAASUVORK5CYII=',
			'F3A7' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7QkNZQximMIaGIIkFNIi0MoQyNIigiDE0Ojo6oIu1sgLJACT3hUatClu6KmplFpL7oOpaGdDMcw0NmIIh1hAQwIDmFtaGQAdUMdYQdLGBCj8qQizuAwA3Tc3caOXzyAAAAABJRU5ErkJggg==',
			'6269' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdklEQVR4nGNYhQEaGAYTpIn7WAMYQxhCGaY6IImJTGFtZXR0CAhAEgtoEWl0bXB0EEEWa2AAijHCxMBOioxatXTp1FVRYUjuC5nCMIXV0WEqit5WhgBWoAmoYowOQDEUO4BuaUB3C2uAaKgDmpsHKvyoCLG4DwAVLcwnu5tjiwAAAABJRU5ErkJggg==',
			'656C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nGNYhQEaGAYTpIn7WANEQxlCGaYGIImJTBFpYHR0CBBBEgtoEWlgbXB0YEEWaxAJYW1gdEB2X2TU1KVLp67MQnZfyBSGRldHRwdkewNagWINgWhiImAxZDtEprC2oruFNYAxBN3NAxV+VIRY3AcAVULLlpSXeQoAAAAASUVORK5CYII=',
			'0135' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nM3QsQ2AMAwEwHeRDTKQU9A/BU2mSQo2gBEoyJRE0DhACVLs7mXLJ6M8KqGn/sUnCsokE03mKHQ5qJ3ziyPS2GScQeQwqPHFrcSy7jEa3zWnyd93E5vMLzhv+MaCalFan6irYqzawf8+7BffAadKyY+adApoAAAAAElFTkSuQmCC',
			'2BFF' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXklEQVR4nGNYhQEaGAYTpIn7WANEQ1hDA0NDkMREpoi0sjYwOiCrC2gVaXRFE2NoRVEHcdO0qWFLQ1eGZiG7LwDTPEYHTPNYGzDFRBow9YaGAt2M7pYBCj8qQizuAwART8i8OGURogAAAABJRU5ErkJggg==',
			'7A4D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7QkMZAhgaHUMdkEVbGUMYWh0dAlDEWFsZpjo6iCCLTRFpdAiEi0HcFDVtZWZmZtY0JPcxOog0ujai6mVtEA11DQ1EERNpAJqHpi4AKhaAKYbq5gEKPypCLO4DAD26zKPKHAasAAAAAElFTkSuQmCC',
			'2F2F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7WANEQx1CGUNDkMREpog0MDo6OiCrC2gVaWBtCEQRYwCKMSDEIG6aNjVs1crM0Cxk9wUA1bUyouhldACKTUEVY20AigWgiokAIaMDqlhoKNAtoWhuGaDwoyLE4j4Av1XIaCTkPR0AAAAASUVORK5CYII=',
			'15AA' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7GB1EQxmmMLQii7E6iDQwhDJMdUASEwWKMTo6BASg6BUJYW0IBKmGu29l1tSlS1dFZk1Dch+jA0OjK0IdQiw0MDQE1Tws6lhbWdHEREMYQ9DFBir8qAixuA8ArBvJhB9osMUAAAAASUVORK5CYII=',
			'8B4F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAX0lEQVR4nGNYhQEaGAYTpIn7WANEQxgaHUNDkMREpoi0MrQ6OiCrC2gVaXSYiioGVhcIFwM7aWnU1LCVmZmhWUjuA6ljbcQ0zzU0ENOORix2oIlB3YwiNlDhR0WIxX0AGgTLPtrcz84AAAAASUVORK5CYII=',
			'2837' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nM3QsRGAMAhA0VCwAQPFDSiCQzgFFmyg7pBMqVKR01JPQ/eOS/4ltcvR9Kd5pQ8ZCghICUYLGs5ZKRgbHcKdJUNLrqFvq2NbW51iH/uexXch+31L16JuHI30bBlyNBFv7uyr/3twbvp2f6PMOWklRp8AAAAASUVORK5CYII=',
			'71E3' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7QkMZAlhDHUIdkEVbGQNYGxgdAlDEWIFiDA0iyGJTGMBiAcjui1oVtTR01dIsJPcxOqCoA0MgH8M8ESxiAWAxVLcENLCGYrh5gMKPihCL+wDhW8mn7dJFyAAAAABJRU5ErkJggg==',
			'0F20' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7GB1EQx1CGVqRxVgDRBoYHR2mOiCJiUwRaWBtCAgIQBILaBUBkoEOIkjui1o6NWzVysysaUjuA6trZYSpQ4hNQRUD2cEQwIBiB9gtDgwobgHpYg0NQHHzQIUfFSEW9wEAwhnK+WdOGt8AAAAASUVORK5CYII=',
			'E531' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7QkNEQxlDGVqRxQIaRBpYGx2moosByVA0sRCGRgeYXrCTQqOmLl01ddVSZPcFNABVIdQhxBoC0O3FIsbayoqmNzSEMQTo5tCAQRB+VIRY3AcA5UjOdeBbiE4AAAAASUVORK5CYII=',
			'8B97' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7WANEQxhCGUNDkMREpoi0Mjo6NIggiQW0ijS6NgSgiIHUsQLFApDctzRqatjKzKiVWUjuA6ljCAloZUAzz6EhYAq6mGNDQAADhlscHbC4GUVsoMKPihCL+wCll8xcfR3M9AAAAABJRU5ErkJggg==',
			'3A26' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nGNYhQEaGAYTpIn7RAMYAhhCGaY6IIkFTGEMYXR0CAhAVtnK2sraEOgggCw2RaTRASiG7L6VUdNWZq3MTM1Cdh9IXSsjmnmioQ5TGB1EUMSA6gJQxQKAeh0dGFD0igaINLqGBqC4eaDCj4oQi/sATHnLuvmu2bcAAAAASUVORK5CYII=',
			'7494' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7QkMZWhlCGRoCkEVbGaYyOjo0oomFsjYEtKKITWF0BYpNCUB2X9TSpSszo6KikNzH6CDSyhAS6ICsl7VBNNShITA0BElMBGgLI9AlyOoCQGKODhhiGG4eoPCjIsTiPgApR80k/sTHvAAAAABJRU5ErkJggg==',
			'6009' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7WAMYAhimMEx1QBITmcIYwhDKEBCAJBbQwtrK6OjoIIIs1iDS6NoQCBMDOykyatrK1FVRUWFI7guZAlIXMBVFbytYrAFVDGSHA4od2NyCzc0DFX5UhFjcBwCs9cvdn8I1tgAAAABJRU5ErkJggg==',
			'207E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7WAMYAlhDA0MDkMREpjCGMDQEOiCrC2hlbUUXY2gVaXRodISJQdw0bdrKrKUrQ7OQ3RcAVDeFEUUvowNQLABVjLWBtZXRAVVMpIExhLUBVSw0FOjmBkYUNw9U+FERYnEfAJ6yyRYClJnuAAAAAElFTkSuQmCC',
			'74F2' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7QkMZWllDA6Y6IIu2MkxlbWAICEAVC2VtYHQQQRabwugKVNcgguy+qKVLl4YCKST3AXW1AtU1ItvB2iAa6goyFUkMaA5I3RRksQCIWACmGGNoyCAIPypCLO4DALh6yw4qbQT5AAAAAElFTkSuQmCC',
			'5245' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAd0lEQVR4nM2QsQ2AMAwEncIbhH0+Bb2REgo2YIuk8AbABhQwJSmNoAQp/u6aO5nOx2Vqab/0pegilZDEMMmspAF0Y75gvbNBqGAIPUzfuJ37Mc/TZPuUFi7I3pqVhKvVMlGH2gLL/MK5WsT2sXQJtQYN/O/DvfRdmVzMg9ydqQ8AAAAASUVORK5CYII=',
			'C52F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7WENEQxlCGUNDkMREWkUaGB0dHZDVBTSKNLA2BKKKNYiEMCDEwE6KWjV16aqVmaFZSO4LaGBodGhlRNMLFJvCiG5Ho0MAqphIKytQJ6oYawhjCGsoqlsGKvyoCLG4DwA9tcmsy6W3lgAAAABJRU5ErkJggg==',
			'DFD3' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAVUlEQVR4nGNYhQEaGAYTpIn7QgNEQ11DGUIdkMQCpog0sDY6OgQgi7UCxRoCGkSwiAUguS9q6dSwpUAyC8l9aOoImidCwC2hAUAxNDcPVPhREWJxHwBcec+DSpvRPwAAAABJRU5ErkJggg==',
			'772C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7QkNFQx1CGaYGIIu2MjQ6OjoEiKCJuTYEOrAgi00BigLFUNwXtWraqpWZWcjuY3RgCGBoZXRAtpcVJDoFVUwEKMoQwIhiRwBQFKRfBE2MNTQA1c0DFH5UhFjcBwDnuMo/sRgGggAAAABJRU5ErkJggg==',
			'4C74' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nM3QsRGAIAxA0aRgAwbCwj4FaZgmFG6QYwObTCl2AS31NOn+wd27gF1G4E/7jk+RA5OQbznUJFR9wxylt823oFGgJiXna62Z7VaK89H5TjH5v8y9EXIeLFGWBKNFQ11lbt08t6/u99ze+A4SFc5fn1V88QAAAABJRU5ErkJggg==',
			'F3AB' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZUlEQVR4nGNYhQEaGAYTpIn7QkNZQximMIY6IIkFNIi0MoQyOgSgiDE0Ojo6OoigirWyNgTC1IGdFBq1KmzpqsjQLCT3oamDm+caGohuXqNrA7qYCBa9rCFAMRQ3D1T4URFicR8AWv7NaSJBoZ4AAAAASUVORK5CYII=',
			'9AC8' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nGNYhQEaGAYTpIn7WAMYAhhCHaY6IImJTGEMYXQICAhAEgtoZW1lbRB0EEERE2l0bWCAqQM7adrUaStTV62amoXkPlZXFHUQ2Coa6trAiGKeANg8VDtEpog0OqK5hTVApNEBzc0DFX5UhFjcBwCDJMylOeENxQAAAABJRU5ErkJggg==',
			'7EAE' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7QkNFQxmmMIYGIIu2ijQwhDI6MKCJMTo6oopNEWlgbQiEiUHcFDU1bOmqyNAsJPcxOqCoA0PWBqBYKKqYSAOmugCsYqKhQDFUNw9Q+FERYnEfACWDyhSlUx83AAAAAElFTkSuQmCC',
			'F610' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7QkMZQximMLQiiwU0sLYyhDBMdUARE2kEqgwIQBVrYJjC6CCC5L7QqGlhq6atzJqG5L6ABtFWJHVw8xywiqHbAXTLFHS3MIYwhjqguHmgwo+KEIv7ALUfzM4xWE2GAAAAAElFTkSuQmCC',
			'3707' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdUlEQVR4nGNYhQEaGAYTpIn7RANEQx2mMIaGIIkFTGFodAhlaBBBVtnK0Ojo6IAqNoWhlbUhAAgR7lsZtWra0lVRK7OQ3TeFIQCorhXF5lZGB1aQTShirA2Mjg4BDChuAdoYyuiA6mag2BRUsYEKPypCLO4DAKl0y2RMvZEkAAAAAElFTkSuQmCC',
			'DEE3' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAU0lEQVR4nGNYhQEaGAYTpIn7QgNEQ1lDHUIdkMQCpog0sDYwOgQgi7WCxBgaRLCIBSC5L2rp1LCloauWZiG5D00dQfNQxLC4BZubByr8qAixuA8A2OzNgvxWkfEAAAAASUVORK5CYII=',
			'BCD0' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXElEQVR4nGNYhQEaGAYTpIn7QgMYQ1lDGVqRxQKmsDa6NjpMdUAWaxVpcG0ICAhAUSfSwNoQ6CCC5L7QqGmrlq6KzJqG5D40dXDzsIlh2oHpFmxuHqjwoyLE4j4A8P3PM4IhQt4AAAAASUVORK5CYII=',
			'381C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpIn7RAMYQximMEwNQBILmMLayhDCECCCrLJVpNExhNGBBVkMpG4KowOy+1ZGrQxbNW1lFor7UNXBzXPAIYZsB9gtU1DdAnIzY6gDipsHKvyoCLG4DwDr+8p6rrPEPQAAAABJRU5ErkJggg==',
			'0B96' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7GB1EQxhCGaY6IImxBoi0Mjo6BAQgiYlMEWl0bQh0EEASC2gVaWUFiiG7L2rp1LCVmZGpWUjuA6ljCAlEMQ8o1ugA1CuCZocjmhg2t2Bz80CFHxUhFvcBAD9hy4M/hMMpAAAAAElFTkSuQmCC',
			'C1D1' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAX0lEQVR4nGNYhQEaGAYTpIn7WEMYAlhDGVqRxURaGQNYGx2mIosFNLIGsDYEhKKINTCAxGB6wU6KAqKlIITkPjR1uMUaMcVEWhlAbkERYw1hDQW6OTRgEIQfFSEW9wEA4WjLNyUaJAoAAAAASUVORK5CYII=',
			'BE39' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAX0lEQVR4nGNYhQEaGAYTpIn7QgNEQxlDGaY6IIkFTBFpYG10CAhAFmsVAZKBDiJo6hgaHWFiYCeFRk0NWzV1VVQYkvsg6hymimCYF9CARQzDDnS3YHPzQIUfFSEW9wEAaarOBuaIWugAAAAASUVORK5CYII=',
			'977C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nGNYhQEaGAYTpIn7WANEQ11DA6YGIImJTGFodGgICBBBEgtoBYkFOrCgigFFHR2Q3Tdt6qppq5auzEJ2H6srQwDDFEYHFJtbgfwAVDGBVtYGRgdGFDtEpog0sDYwoLiFNQAshuLmgQo/KkIs7gMAD+HK0mi8/isAAAAASUVORK5CYII=',
			'13FA' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZUlEQVR4nGNYhQEaGAYTpIn7GB1YQ1hDA1qRxVgdRFpZGximOiCJiTowNLo2MAQEoOhlAKpjdBBBct/KrFVhS0NXZk1Dch+aOpgY0DzG0BBMMTR1Ihh6RUOAbkYTG6jwoyLE4j4A9SPH2o8DIIkAAAAASUVORK5CYII=',
			'81FB' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXUlEQVR4nGNYhQEaGAYTpIn7WAMYAlhDA0MdkMREpjAGsDYwOgQgiQW0soLFRFDUMSCrAztpadSqqKWhK0OzkNyHpg5qHgOGedjEsOkFuiQUKIbi5oEKPypCLO4DAL17yJrhEAB3AAAAAElFTkSuQmCC',
			'D91F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7QgMYQximMIaGIIkFTGFtZQhhdEBWF9Aq0uiIRcxhClwM7KSopUuXZk1bGZqF5L6AVsZAJHVQMYZGTDEWTDGQW9DEQG5mDHVEERuo8KMixOI+APO8yyCBbWJ8AAAAAElFTkSuQmCC',
			'0295' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAd0lEQVR4nGNYhQEaGAYTpIn7GB0YQxhCGUMDkMRYA1hbGR0dHZDViUwRaXRtCEQRC2hlAIm5OiC5L2rpqqUrMyOjopDcB1Q3hSEkoEEEVW8AkEQRE5kCdA3QDhFUtzQwOjoEILuP0UE01CGUYarDIAg/KkIs7gMALkzKuS5z6hsAAAAASUVORK5CYII=',
			'3157' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7RAMYAlhDHUNDkMQCpjAGsAJpEWSVrayYYlOAeqcC1SO5b2XUqqilmVkrs5DdB1QHVNWKYnMrWGwKuhhrQ0AAA4pbGAIYHR0dUN3MGsoQyogiNlDhR0WIxX0Ao8vJHUZar6wAAAAASUVORK5CYII=',
			'222C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7WAMYQxhCGaYGIImJTGFtZXR0CBBBEgtoFWl0bQh0YEHW3crQ6AAUQ3HftFVLV63MzEJxXwDDFIZWRgdke4E8oCiqGCtYlBHFDhGoKLJbQkNFQ11DA1DcPFDhR0WIxX0AX5vJzbLkD6QAAAAASUVORK5CYII=',
			'568E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7QkMYQxhCGUMDkMQCGlhbGR0dHRhQxEQaWRsCUcQCA0QakNSBnRQ2bVrYqtCVoVnI7msVxTCPoVWk0RXNvAAsYiJTMN3CGoDp5oEKPypCLO4DADGzyb+QdeYDAAAAAElFTkSuQmCC',
			'FC48' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7QkMZQxkaHaY6IIkFNLA2OrQ6BASgiIk0OEx1dBBBE2MIhKsDOyk0atqqlZlZU7OQ3AdSBzQRwzzW0EAM8xwa0e0A6sTQi+nmgQo/KkIs7gMA+0rPQxF1KqsAAAAASUVORK5CYII=',
			'61A0' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7WAMYAhimMLQii4lMYQxgCGWY6oAkFtDCGsDo6BAQgCzWwBDA2hDoIILkvsioVVFLV0VmTUNyX8gUFHUQva1AsVAsYg0BKHaIgPUGoLiFFaiTFaR6EIQfFSEW9wEARnPK6zl/3DgAAAAASUVORK5CYII=',
			'CEFB' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWUlEQVR4nGNYhQEaGAYTpIn7WENEQ1lDA0MdkMREWkUaWBsYHQKQxAIaIWIiyGINKOrATopaNTVsaejK0Cwk96GpQxETIWAHNreA3dzAiOLmgQo/KkIs7gMAYrLKs07TGS0AAAAASUVORK5CYII=',
			'70C1' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7QkMZAhhCHVpRRFsZQxgdAqaiirG2sjYIhKKITRFpdAXKoLgvatrK1FWrliK7j9EBRR0YsjZgiok0gO1AEQtoALsFTQzs5tCAQRB+VIRY3AcAA8fLZ4bEGvoAAAAASUVORK5CYII=',
			'E607' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7QkMYQximMIaGIIkFNLC2MoQyNIigiIk0Mjo6oIs1sALJACT3hUZNC1u6KmplFpL7AhpEW4HqWhnQzHNtCJiCLubo6BDAgOEWRgcsbkYRG6jwoyLE4j4ABpnMsSmpEDEAAAAASUVORK5CYII=',
			'C5F6' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7WENEQ1lDA6Y6IImJtIo0sDYwBAQgiQU0gsQYHQSQxRpEQkBiyO6LWjV16dLQlalZSO4DmtPo2sCIah5EzEEE1Q4MMZFW1lZ0t7CGMALtZUBx80CFHxUhFvcBAJRXy7rCoHCuAAAAAElFTkSuQmCC',
			'814F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7WAMYAhgaHUNDkMREpjAGMLQ6OiCrC2gFqpyKKiYyBag3EC4GdtLSqFVRKzMzQ7OQ3AdSx9qIbh5QLDQQQ4yhEYsdaGKsQJ3oYgMVflSEWNwHALTOyIL55wqZAAAAAElFTkSuQmCC',
			'D7D8' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7QgNEQ11DGaY6IIkFTGFodG10CAhAFmsFijUEOoigirWyNgTA1IGdFLV01bSlq6KmZiG5D6guAEkdVIzRgRXDPNYGDLEpIg2saG4JDQCKobl5oMKPihCL+wDL787uNJc2UQAAAABJRU5ErkJggg==',
			'E37A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7QkNYQ1hDA1qRxQIaRID8gKkOKGIMjQ4NAQEBqGKtDI2ODiJI7guNWhW2aunKrGlI7gOrm8IIU4cwL4AxNARNzNEBXZ1IK2sDqhjYzWhiAxV+VIRY3AcASOPMrGSGEgUAAAAASUVORK5CYII='        
        );
        $this->text = array_rand( $images );
        return $images[ $this->text ] ;    
    }
    
    function out_processing_gif(){
        $image = dirname(__FILE__) . '/processing.gif';
        $base64_image = "R0lGODlhFAAUALMIAPh2AP+TMsZiALlcAKNOAOp4ANVqAP+PFv///wAAAAAAAAAAAAAAAAAAAAAAAAAAACH/C05FVFNDQVBFMi4wAwEAAAAh+QQFCgAIACwAAAAAFAAUAAAEUxDJSau9iBDMtebTMEjehgTBJYqkiaLWOlZvGs8WDO6UIPCHw8TnAwWDEuKPcxQml0Ynj2cwYACAS7VqwWItWyuiUJB4s2AxmWxGg9bl6YQtl0cAACH5BAUKAAgALAEAAQASABIAAAROEMkpx6A4W5upENUmEQT2feFIltMJYivbvhnZ3Z1h4FMQIDodz+cL7nDEn5CH8DGZhcLtcMBEoxkqlXKVIgAAibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkphaA4W5upMdUmDQP2feFIltMJYivbvhnZ3V1R4BNBIDodz+cL7nDEn5CH8DGZAMAtEMBEoxkqlXKVIg4HibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpjaE4W5tpKdUmCQL2feFIltMJYivbvhnZ3R0A4NMwIDodz+cL7nDEn5CH8DGZh8ONQMBEoxkqlXKVIgIBibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpS6E4W5spANUmGQb2feFIltMJYivbvhnZ3d1x4JMgIDodz+cL7nDEn5CH8DGZgcBtMMBEoxkqlXKVIggEibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpAaA4W5vpOdUmFQX2feFIltMJYivbvhnZ3V0Q4JNhIDodz+cL7nDEn5CH8DGZBMJNIMBEoxkqlXKVIgYDibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpz6E4W5tpCNUmAQD2feFIltMJYivbvhnZ3R1B4FNRIDodz+cL7nDEn5CH8DGZg8HNYMBEoxkqlXKVIgQCibbK9YLBYvLtHH5K0J0IACH5BAkKAAgALAEAAQASABIAAAROEMkpQ6A4W5spIdUmHQf2feFIltMJYivbvhnZ3d0w4BMAIDodz+cL7nDEn5CH8DGZAsGtUMBEoxkqlXKVIgwGibbK9YLBYvLtHH5K0J0IADs=";
        $binary = is_file($image) ? join("",file($image)) : base64_decode($base64_image); 
        header("Cache-Control: post-check=0, pre-check=0, max-age=0, no-store, no-cache, must-revalidate");
        header("Pragma: no-cache");
        header("Content-type: image/gif");
        echo $binary;
    }

}
# end of class phpfmgImage
# ------------------------------------------------------
# end of module : captcha


# module user
# ------------------------------------------------------
function phpfmg_user_isLogin(){
    return ( isset($_SESSION['authenticated']) && true === $_SESSION['authenticated'] );
}


function phpfmg_user_logout(){
    session_destroy();
    header("Location: admin.php");
}

function phpfmg_user_login()
{
    if( phpfmg_user_isLogin() ){
        return true ;
    };
    
    $sErr = "" ;
    if( 'Y' == $_POST['formmail_submit'] ){
        if(
            defined( 'PHPFMG_USER' ) && PHPFMG_USER == $_POST['Username'] &&
            defined( 'PHPFMG_PW' )   && PHPFMG_PW   == $_POST['Password']
        ){
             $_SESSION['authenticated'] = true ;
             return true ;
             
        }else{
            $sErr = 'Login failed. Please try again.';
        }
    };
    
    // show login form 
    phpfmg_admin_header();
?>
<form name="frmFormMail" action="" method='post' enctype='multipart/form-data'>
<input type='hidden' name='formmail_submit' value='Y'>
<br><br><br>

<center>
<div style="width:380px;height:260px;">
<fieldset style="padding:18px;" >
<table cellspacing='3' cellpadding='3' border='0' >
	<tr>
		<td class="form_field" valign='top' align='right'>Email :</td>
		<td class="form_text">
            <input type="text" name="Username"  value="<?php echo $_POST['Username']; ?>" class='text_box' >
		</td>
	</tr>

	<tr>
		<td class="form_field" valign='top' align='right'>Password :</td>
		<td class="form_text">
            <input type="password" name="Password"  value="" class='text_box'>
		</td>
	</tr>

	<tr><td colspan=3 align='center'>
        <input type='submit' value='Login'><br><br>
        <?php if( $sErr ) echo "<span style='color:red;font-weight:bold;'>{$sErr}</span><br><br>\n"; ?>
        <a href="admin.php?mod=mail&func=password">I forgot my password</a>    
    </td></tr>
</table>
</fieldset>
</div>
<script type="text/javascript">
    document.frmFormMail.Username.focus();
</script>
</form>
<?php
    phpfmg_admin_footer();
}


function phpfmg_mail_password(){
    phpfmg_admin_header();
    if( defined( 'PHPFMG_USER' ) && defined( 'PHPFMG_PW' ) ){
        mail( PHPFMG_USER, "Your password", "Here is the password for your form admin panel:\n\nUsername: " . PHPFMG_USER . "\nPassword: " . PHPFMG_PW . "\n\n" );
        echo "<center>Your password has been sent.<br><br><a href='admin.php'>Click here to login again</a></center>";
    };   
    phpfmg_admin_footer();
}


function phpfmg_writable_check(){
 
    if( is_writable( dirname(PHPFMG_SAVE_FILE) ) && is_writable( dirname(PHPFMG_EMAILS_LOGFILE) )  ){
        return ;
    };
?>
<style type="text/css">
    .fmg_warning{
        background-color: #F4F6E5;
        border: 1px dashed #ff0000;
        padding: 16px;
        color : black;
        margin: 10px;
        line-height: 180%;
        width:80%;
    }
    
    .fmg_warning_title{
        font-weight: bold;
    }

</style>
<br><br>
<div class="fmg_warning">
    <div class="fmg_warning_title">Your form data or email traffic log is NOT saving.</div>
    The form data (<?php echo PHPFMG_SAVE_FILE ?>) and email traffic log (<?php echo PHPFMG_EMAILS_LOGFILE?>) will be created aumotically when the form is submitted. 
    However, the script doesn't have writable permission to create those files. In order to save your valuable information, please set the directory to writable.
     If you don't know how to do it, please ask for help from your web Administrator or Technical Support of your hosting company.   
</div>
<br><br>
<?php
}


function phpfmg_log_view(){
    $n = isset($_REQUEST['file'])  ? $_REQUEST['file']  : '';
    $files = array(
        1 => PHPFMG_EMAILS_LOGFILE,
        2 => PHPFMG_SAVE_FILE,
    );
    
    phpfmg_admin_header();
   
    $file = $files[$n];
    if( is_file($file) ){
        if( 1== $n ){
            echo "<pre>\n";
            echo join("",file($file) );
            echo "</pre>\n";
        }else{
            $man = new phpfmgDataManager();
            $man->displayRecords();
        };
     

    }else{
        echo "<b>No form data found.</b>";
    };
    phpfmg_admin_footer();
}


function phpfmg_log_download(){
    $n = isset($_REQUEST['file'])  ? $_REQUEST['file']  : '';
    $files = array(
        1 => PHPFMG_EMAILS_LOGFILE,
        2 => PHPFMG_SAVE_FILE,
    );

    $file = $files[$n];
    if( is_file($file) ){
        phpfmg_util_download( $file, PHPFMG_SAVE_FILE == $file ? 'form-data.csv' : 'email-traffics.txt', true, 1 ); // skip the first line
    }else{
        phpfmg_admin_header();
        echo "<b>No email traffic log found.</b>";
        phpfmg_admin_footer();
    };

}




function phpfmg_util_download($file, $filename='', $toCSV = false, $skipN = 0 ){
    if (!is_file($file)) return false ;

    set_time_limit(0);
    while (@ob_end_clean()); // no output buffering !
    
    $len = filesize($file);
    $filename = basename( '' == $filename ? $file : $filename );
    $file_extension = strtolower(substr(strrchr($filename,"."),1));

    switch( $file_extension ) {
        case "pdf": $ctype="application/pdf"; break;
        case "exe": $ctype="application/octet-stream"; break;
        case "zip": $ctype="application/zip"; break;
        case "doc": $ctype="application/msword"; break;
        case "xls": $ctype="application/vnd.ms-excel"; break;
        case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
        case "gif": $ctype="image/gif"; break;
        case "png": $ctype="image/png"; break;
        case "jpeg":
        case "jpg": $ctype="image/jpg"; break;
        case "mp3": $ctype="audio/mpeg"; break;
        case "wav": $ctype="audio/x-wav"; break;
        case "mpeg":
        case "mpg":
        case "mpe": $ctype="video/mpeg"; break;
        case "mov": $ctype="video/quicktime"; break;
        case "avi": $ctype="video/x-msvideo"; break;
        //The following are for extensions that shouldn't be downloaded (sensitive stuff, like php files)
        case "php":
        case "htm":
        case "html": 
                $ctype="text/plain"; break;
        default: $ctype="application/force-download";
    }
    
    //Begin writing headers
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: public"); 
    header("Content-Description: File Transfer");
    
    //Use the switch-generated Content-Type
    header("Content-Type: $ctype");
    //Force the download
    $header="Content-Disposition: attachment; filename=".$filename.";";
    header($header );
    header("Content-Transfer-Encoding: binary");
    header("Content-Length: ".$len);

    $i = 0 ;
    $fp = @fopen($file, 'rb');
    while( !feof($fp) && (0 == connection_status()) ) { 


        flush();
        $i ++ ;
        if( $toCSV ){ 
            $line = fgets($fp);
            if($i > $skipN){ // skip lines
                $line = str_replace( chr(0x09), ',', $line );
                echo phpfmg_data2record( $line, false );
            }; 
            
        }else{
            print( fread($fp, 1024*100) );
        };
        
    }; 
    fclose ($fp);
    
    return true ;
}
?>