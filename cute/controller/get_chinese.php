<? 
Function get_ChineseStroke($Word){ 
/*   
���嵧�����Ǥ��X��Ӫ� 
�z�w�w�s�w�w�w�w�w�s�w�w�w�w�w�w�w�w�w�w�{ 
�x�����x�`�Φr��  �x���`�Φr��          �x 
�u�w�w�q�w�w�w�w�w�q�w�w�w�w�w�w�w�w�w�w�t 
�x 01 �xA440-A441 �x                    �x 
�x 02 �xA442-A453 �xC940-C944           �x 
�x 03 �xA454-A47E �xC945-C94C           �x 
�x 04 �xA4A1-A4FD �xC94D-C95C           �x 
�x 05 �xA4FE-A5DF �xC95D-C9AA           �x 
�x 06 �xA5E0-A6E9 �xC9AB-C959           �x 
�x 07 �xA6EA-A8C2 �xCA5A-CBB0           �x 
�x 08 �xA8C3-AB44 �xCBB1-CDDC           �x 
�x 09 �xAB45-ADBB �xCDDD-D0C7 F9DA      �x 
�x 10 �xADBC-B0AD �xD0C8-D44A           �x 
�x 11 �xB0AE-B3C2 �xD44B-D850           �x 
�x 12 �xB3C3-B6C3 �xD851-DCB0 F9DB      �x 
�x 13 �xB6C4-B9AB �xDCB1-E0EF F9D6-F9D8 �x 
�x 14 �xB9AC-BBF4 �xE0F0-E4E5           �x 
�x 15 �xBBF5-BEA6 �xE4E6-E8F3 F9DC      �x 
�x 16 �xBEA7-C074 �xE8F4-ECB8 F9D9      �x 
�x 17 �xC075-C24E �xECB9-EFB6           �x 
�x 18 �xC24F-C35E �xEFB7-F1EA           �x 
�x 19 �xC35F-C454 �xF1EB-F3FC           �x 
�x 20 �xC455-C4D6 �xF3FD-F5BF           �x 
�x 21 �xC3D7-C56A �xF5C0-F6D5           �x 
�x 22 �xC56B-C5C7 �xF6D6-F7CF           �x 
�x 23 �xC5C8-C5C7 �xF6D6-F7CF           �x 
�x 24 �xC5F1-C654 �xF8A5-F8ED           �x 
�x 25 �xC655-C664 �xF8E9-F96A           �x 
�x 26 �xC665-C66B �xF96B-F9A1           �x 
�x 27 �xC66C-C675 �xF9A2-F9B9           �x 
�x 28 �xC676-C67A �xF9BA-F9C5           �x 
�x 29 �xC67B-C67E �xF9C6-F9DC           �x 
�|�w�w�r�w�w�w�w�w�r�w�w�w�w�w�w�w�w�w�w�} 
*/    
   If(Strlen($Word) != 2   ) Return false; ## �D���줸 
   If(Ord($Word[0]) <  0xA1) Return false; ## �D����X 
    
   $Code=Hexdec(Bin2hex($Word));      
    
   $StrokeArray=Array( 
                   Array(1 ,0xA440,0xA441), ## �`�Φr�� 
                   Array(2 ,0xA442,0xA453), 
                   Array(3 ,0xA454,0xA47E), 
                   Array(4 ,0xA4A1,0xA4FD), 
                   Array(5 ,0xA4FE,0xA5DF), 
                   Array(6 ,0xA5E0,0xA6E9), 
                   Array(7 ,0xA6EA,0xA8C2), 
                   Array(8 ,0xA8C3,0xAB44), 
                   Array(9 ,0xAB45,0xADBB), 
                   Array(10,0xADBC,0xB0AD), 
                   Array(11,0xB0AE,0xB3C2), 
                   Array(12,0xB3C3,0xB6C3), 
                   Array(13,0xB6C4,0xB9AB), 
                   Array(14,0xB9AC,0xBBF4), 
                   Array(15,0xBBF5,0xBEA6), 
                   Array(16,0xBEA7,0xC074), 
                   Array(17,0xC075,0xC24E), 
                   Array(18,0xC24F,0xC35E), 
                   Array(19,0xC35F,0xC454), 
                   Array(20,0xC455,0xC4D6), 
                   Array(21,0xC3D7,0xC56A), 
                   Array(22,0xC56B,0xC5C7), 
                   Array(23,0xC5C8,0xC5C7), 
                   Array(24,0xC5F1,0xC654), 
                   Array(25,0xC655,0xC664), 
                   Array(26,0xC665,0xC66B), 
                   Array(27,0xC66C,0xC675), 
                   Array(28,0xC676,0xC67A), 
                   Array(29,0xC67B,0xC67E), 
                   Array(2 ,0xC940,0xC944), ## ���`�Φr��I 
                   Array(3 ,0xC945,0xC94C),  
                   Array(4 ,0xC94D,0xC95C),  
                   Array(5 ,0xC95D,0xC9AA),  
                   Array(6 ,0xC9AB,0xC959),  
                   Array(7 ,0xCA5A,0xCBB0),  
                   Array(8 ,0xCBB1,0xCDDC),  
                   Array(9 ,0xCDDD,0xD0C7),  
                   Array(10,0xD0C8,0xD44A),  
                   Array(11,0xD44B,0xD850),  
                   Array(12,0xD851,0xDCB0),  
                   Array(13,0xDCB1,0xE0EF),  
                   Array(14,0xE0F0,0xE4E5),  
                   Array(15,0xE4E6,0xE8F3),  
                   Array(16,0xE8F4,0xECB8),  
                   Array(17,0xECB9,0xEFB6),  
                   Array(18,0xEFB7,0xF1EA),  
                   Array(19,0xF1EB,0xF3FC),  
                   Array(20,0xF3FD,0xF5BF),  
                   Array(21,0xF5C0,0xF6D5),  
                   Array(22,0xF6D6,0xF7CF),  
                   Array(23,0xF6D6,0xF7CF),  
                   Array(24,0xF8A5,0xF8ED),  
                   Array(25,0xF8E9,0xF96A),  
                   Array(26,0xF96B,0xF9A1),  
                   Array(27,0xF9A2,0xF9B9),  
                   Array(28,0xF9BA,0xF9C5),  
                   Array(29,0xF9C6,0xF9DC), 
                   Array(9 ,0xF9DA,0xF9DA), ## ���`�Φr��II 
                   Array(12,0xF9DB,0xF9DB), 
                   Array(13,0xF9D6,0xF9D8), 
                   Array(15,0xF9DC,0xF9DC), 
                   Array(16,0xF9D9,0xF9D9),
                   Array(30,0xC67B,0xC67D), ## ���`�Φr��III�ץ��X
                   Array(30,0xF9CC,0xF9CF),  
                   Array(31,0xF9C6,0xF9C6),  
                   Array(31,0xF9D0,0xF9D0),  
                   Array(32,0xF9D1,0xF9D1),  
                   Array(33,0xC67E,0xC67E),  
                   Array(33,0xF9D2,0xF9D2),  
                   Array(34,0xF9D3,0xF9D3),  
                   Array(36,0xF9D4,0xF9D5)  
                ); 
                    
   For($i=0;$i<Count($StrokeArray);$i++): 
     If($StrokeArray[$i][1] <= $Code && $StrokeArray[$i][2] >= $Code): 
         Return $StrokeArray[$i][0]; 
     EndIf; 
   EndFor;  
}   
?>