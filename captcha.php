<?php
/*
���ͤ@��CAPTCHA�ϧ�
�}�l���w�q�@�ӷs�v���A�w�q����e
*/
$w = 120;
$h = 38;
$gfx = imagecreatetruecolor($w,$h);

//�}�ҤϿ����\��A�o�˦��u����n��
imageantialias($gfx,true);

//�I����]���զ�
$white = imagecolorallocate($gfx,255,255,255);
imagefilledrectangle($gfx,0,0,$w-1,$h-1,$white);

//���ͤ@��6-8�Ӧr�����r��A�u���j�g�r��
$str = '';
foreach(range(0,rand(3,3))as $r) {
  $str .= chr(rand(65,90));
}

//�μe�װ��H���סA��X�C�@�Ӧr�����X�A��m
$pos = $w / strlen($str);

//�{�b�ڭ̥i�H�ΰj��L�X�o�Ǧr��
foreach(range(0,strlen($str)-1) as $s) {
  //�H�����ͤ@�ӦǶ��C��A���u��'����t��'
  $shade = rand(0,100);

  //�ŧi�o���C��
  $tmpgray = imagecolorallocate($gfx,$shade,$shade,$shade);

//�{�b�ڭ̥i�H�e�X�o�Ӧr���A�ɶq�Υզ�⥦�d��
imagettftext($gfx,//��ø�s���ϧΪ���
            rand($h/3,$h/2),//�r���j�p�A����1/3����1/2��
            rand(-30,30),//�ɱר��סA�ܤƫܤj
            $s*$pos+($pos*.4),//x,�ɶq�����䥭��
            rand($h*.5,$h*.7),//y,����@�b�Χ�C�@�I�������ܤ�
            $tmpgray,//���K�W���C�� - �Ǧ�
            'font/arial.ttf',//���ϥΪ��r��
            $str{$s}//�L�X�o�Ӧr��
            );
}

//�A�ӥΦU�ئǦ�u����t�K���ӭI���W
//�q�t�ƪ����פ@���e��e�סA�T�O�C�ӪF�賣���e��
foreach(range(-$h,$w,5) as $x){
  //�H�����ͤ@�ӦǦ⳱�v�A�����O�̲`��
  $shade = rand(50,254);
  $tmpgray = imagecolorallocate($gfx,$shade,$shade,$shade);

  //�q�o�̶}�l�e����u�A�@���q�﨤�u�e�U�ӡA�@���﨤�u�e�W�h
  //���O�εy�y�ɱת�����ø�s
//  imageline($gfx,$x,0,$x+$h+rand(0,25),$h-1,$tmpgray);
//  imageline($gfx,$x,$h-1,$x+$h+rand(0,25),0,$tmpgray);
}

//�ڭ̤w�g�����ާ@�A����X���e�A�N�u�����r��s�Jsession�ܼƸ�
session_start();
$_SESSION['captcha'] = $str;

//���s�������D�ڭ̷ǳƿ�XPNG
header('Content-type:image/png');

//�M���X�ڭ̪��v��
imagepng($gfx);
?>