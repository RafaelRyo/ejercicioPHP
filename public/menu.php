<?php
  $menu = array(
    'hamburguesa'  => "Hamburguesas",
    'perro' => "Perros Calientes",
  );

  $submenu = array();
  $submenu['hamburguesa'] = array(
    'vegetariana'  => "Vegetariana",
    'carne'=> "Carne",
  );  
  $submenu['perro'] = array(
    'perroCamp' => "Perro Campesino",
    'perroGr'=> "Perro Grande",
    'perroEsp' => "Perro Especial",
  );


  $submenu2 = array();
  $submenu2['vegetariana'] = array(
    'lenteja'  => "Lentejas",
    'espinaca'=> "Espinacas",
  );
  $submenu2['carne'] = array(
    'res'  => "Res",
    'cerdo'=> "Cerdo",
    'pollo'=> "Pollo",
  );
?>