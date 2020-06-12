<?php
/*
產生一個CAPTCHA圖形
開始先定義一個新影像，定義其長寬
*/
$w = 120;
$h = 38;
$gfx = imagecreatetruecolor($w,$h);

//開啟反鋸齒功能，這樣曲線比較好看
imageantialias($gfx,true);

//背景色設為白色
$white = imagecolorallocate($gfx,255,255,255);
imagefilledrectangle($gfx,0,0,$w-1,$h-1,$white);

//產生一個6-8個字元的字串，只有大寫字母
$str = '';
foreach(range(0,rand(3,3))as $r) {
  $str .= chr(rand(65,90));
}

//用寬度除以長度，找出每一個字元的合適位置
$pos = $w / strlen($str);

//現在我們可以用迴圈印出這些字元
foreach(range(0,strlen($str)-1) as $s) {
  //隨機產生一個灰階顏色，但只有'比較暗的'
  $shade = rand(0,100);

  //宣告這個顏色
  $tmpgray = imagecolorallocate($gfx,$shade,$shade,$shade);

//現在我們可以畫出這個字元，盡量用白色把它搞亂
imagettftext($gfx,//欲繪製的圖形物件
            rand($h/3,$h/2),//字型大小，介於1/3高到1/2高
            rand(-30,30),//傾斜角度，變化很大
            $s*$pos+($pos*.4),//x,盡量讓兩邊平衡
            rand($h*.5,$h*.7),//y,介於一半或更低一點之間做變化
            $tmpgray,//欲貼上的顏色 - 灰色
            'font/arial.ttf',//欲使用的字型
            $str{$s}//印出這個字元
            );
}

//再來用各種灰色線條交差貼於整個背景上
//從負數的高度一直畫到寬度，確保每個東西都有畫到
foreach(range(-$h,$w,5) as $x){
  //隨機產生一個灰色陰影，但不是最深的
  $shade = rand(50,254);
  $tmpgray = imagecolorallocate($gfx,$shade,$shade,$shade);

  //從這裡開始畫兩條線，一條從對角線畫下來，一條對角線畫上去
  //分別用稍稍傾斜的角度繪製
//  imageline($gfx,$x,0,$x+$h+rand(0,25),$h-1,$tmpgray);
//  imageline($gfx,$x,$h-1,$x+$h+rand(0,25),0,$tmpgray);
}

//我們已經完成操作，但輸出之前，將真正的字串存入session變數裡
session_start();
$_SESSION['captcha'] = $str;

//讓瀏覽器知道我們準備輸出PNG
header('Content-type:image/png');

//然後輸出我們的影像
imagepng($gfx);
?>