<?php        // retrieve form data   
      $a = $_POST['a_coeff'];    
     $b = $_POST['b_coeff'];   
      $c = $_POST['c_coeff'];      
  // calculate the discriminant    
     $dsq = $b * $b - 4 * $a * $c;  
       if ($dsq >= 0){  
           $x1 = (- $b + sqrt($dsq))/2/$a;  
           $x2 = $c/$a/$x1;   
          echo "<br> Equation has two real roots $x1 $x2<br>";        
}        else {   
          echo "<br>Complex roots!<br>";    
         $re = -$b/$a/2;    
         $im = sqrt(-$dsq)/2/$a;  
           echo "<br> Real part $re Imag part $im <br>";                    
}        ?>    
 
 </body></html> 