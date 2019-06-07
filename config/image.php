<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

return array(

    /*
     |.....................................................................
     |  images paths
     |.....................................................................
     |
     */
    'user_image_path' => str_replace('\\','/',public_path()). '/uploads/profile_images/',
    

    /*
     |...................................................................../
     |  images urls
     |.....................................................................
     |
     */
    'user_image_url' => '/uploads/profile_images/',
    

    /*
     |.....................................................................
     |  User Images
     |.....................................................................
     |
     */

    /*
     |.....................................................................
     |  Image thumbnail sizes
     |.....................................................................
     |
     */
     
    'small_thumbnail_height' => '100',
    'small_thumbnail_width' => '200',

    'medium_thumbnail_height' => '480',
    'medium_thumbnail_width' => '640',

    'large_thumbnail_height' => '600',
    'large_thumbnail_width' => '800'

);
