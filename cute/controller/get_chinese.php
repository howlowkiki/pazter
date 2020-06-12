<? 
Function get_ChineseStroke($Word){ 
/*   
中文筆劃順序內碼對照表 
┌──┬─────┬──────────┐ 
│筆劃│常用字區  │次常用字區          │ 
├──┼─────┼──────────┤ 
│ 01 │A440-A441 │                    │ 
│ 02 │A442-A453 │C940-C944           │ 
│ 03 │A454-A47E │C945-C94C           │ 
│ 04 │A4A1-A4FD │C94D-C95C           │ 
│ 05 │A4FE-A5DF │C95D-C9AA           │ 
│ 06 │A5E0-A6E9 │C9AB-C959           │ 
│ 07 │A6EA-A8C2 │CA5A-CBB0           │ 
│ 08 │A8C3-AB44 │CBB1-CDDC           │ 
│ 09 │AB45-ADBB │CDDD-D0C7 F9DA      │ 
│ 10 │ADBC-B0AD │D0C8-D44A           │ 
│ 11 │B0AE-B3C2 │D44B-D850           │ 
│ 12 │B3C3-B6C3 │D851-DCB0 F9DB      │ 
│ 13 │B6C4-B9AB │DCB1-E0EF F9D6-F9D8 │ 
│ 14 │B9AC-BBF4 │E0F0-E4E5           │ 
│ 15 │BBF5-BEA6 │E4E6-E8F3 F9DC      │ 
│ 16 │BEA7-C074 │E8F4-ECB8 F9D9      │ 
│ 17 │C075-C24E │ECB9-EFB6           │ 
│ 18 │C24F-C35E │EFB7-F1EA           │ 
│ 19 │C35F-C454 │F1EB-F3FC           │ 
│ 20 │C455-C4D6 │F3FD-F5BF           │ 
│ 21 │C3D7-C56A │F5C0-F6D5           │ 
│ 22 │C56B-C5C7 │F6D6-F7CF           │ 
│ 23 │C5C8-C5C7 │F6D6-F7CF           │ 
│ 24 │C5F1-C654 │F8A5-F8ED           │ 
│ 25 │C655-C664 │F8E9-F96A           │ 
│ 26 │C665-C66B │F96B-F9A1           │ 
│ 27 │C66C-C675 │F9A2-F9B9           │ 
│ 28 │C676-C67A │F9BA-F9C5           │ 
│ 29 │C67B-C67E │F9C6-F9DC           │ 
└──┴─────┴──────────┘ 
*/    
   If(Strlen($Word) != 2   ) Return false; ## 非雙位元 
   If(Ord($Word[0]) <  0xA1) Return false; ## 非中文碼 
    
   $Code=Hexdec(Bin2hex($Word));      
    
   $StrokeArray=Array( 
                   Array(1 ,0xA440,0xA441), ## 常用字區 
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
                   Array(2 ,0xC940,0xC944), ## 次常用字區I 
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
                   Array(9 ,0xF9DA,0xF9DA), ## 次常用字區II 
                   Array(12,0xF9DB,0xF9DB), 
                   Array(13,0xF9D6,0xF9D8), 
                   Array(15,0xF9DC,0xF9DC), 
                   Array(16,0xF9D9,0xF9D9),
                   Array(30,0xC67B,0xC67D), ## 次常用字區III修正碼
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