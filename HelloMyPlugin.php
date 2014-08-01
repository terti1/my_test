<?php

/*
Plugin Name: HelloMyPlugin
Plugin URI: http://localhost/wor
Description: Hello write
Version: 1.0.0
Author: Artem
*/

/*
RUSSIA!
*/


function my_instal(){
 
    global $wpdb;
    $mytable=$wpdb->prefix.mytable;
   
$wpdb->query(" CREATE TABLE
        ".$mytable." (
id MEDIUMINT NOT NULL AUTO_INCREMENT,
name_f VARCHAR(255) NOT NULL,
name_s VARCHAR(255) NOT NULL,
PRIMARY KEY(id)
)
  
  ");    

}
function my_uninstal(){
   global $wpdb;
   $mytable=$wpdb->prefix.mytable;
   $delete_query="DROP TABLE ".$mytable.";";
   $wpdb->query($delete_query);
    
    
}

function my_admin_menu(){
    
    add_options_page('Настройки тестового плагина', 'Тест плагин', 8, 'test', 'option_menu_admin_my');
}
function option_menu_admin_my(){
    
    echo "<h1 style='text-align:center'>Настройки тестового плагина!</h1>";
    echo "<h3>Добавленеи информации в базу данных:</h3>";
    add_information_bd();
    echo "<h3>Все предыдущие записи</h3>";
    show_record_bd();
}

function add_information_bd(){
  global $wpdb;
  $mytable=$wpdb->prefix.mytable;
  
  if(isset($_REQUEST['butadd'])){
      
    $one=$_REQUEST['one'];
    $two=$_REQUEST['two'];
        if(empty($one)||empty($two)){
            echo "Нет значенией";
        } else {
    $wpdb->insert(
    $mytable,  
    array( 'name_f' => $one, 'name_s' =>$two),  
    array( '%s', '%s'));  
        }
            echo "<form method='post' action='".$_SERVER['PHP_SELF']."?page=test'>";
      echo "
<input type='text' name='one'>
<br>
<input type='text' name='two'>
<br>
<input type='submit' value='Добавить' name='butadd' >
";
      echo "</form>";
  }
  else {
      echo "<form method='post' action='".$_SERVER['PHP_SELF']."?page=test'>";
      echo "
<input type='text' name='one'>
<br>
<input type='text' name='two'>
<br>
<input type='submit' value='Добавить' name='butadd' >
";
      echo "</form>";
     
      
  }
    
}
function show_record_bd(){
    global $wpdb;
  $mytable=$wpdb->prefix.mytable;  
    $data_b=$wpdb->get_results("SELECT * FROM wp_mytable");
    $id_record_delete=$_REQUEST['sec'];
   
   if(isset($_REQUEST['delete'])){
        
        $wpdb->query("DELETE FROM $mytable WHERE id=$id_record_delete");
      
    }
   if(isset($_REQUEST['edit'])){
       $one_f=$_REQUEST['one'];
       $two_s=$_REQUEST['two'];
       $wpdb->update(
               $mytable,
               array('name_f'=>$one_f, 'name_s'=>$two_s),
               array('id'=>$id_record_delete),
               array( '%s', '%s'), 
               array( '%d' )
               );
               
   }
show_r($data_b);
}

    function show_r($data_bd){
             foreach ($data_bd as $show_data){
        echo "<form style='float:left; border:1px solid; padding:5px;' method='post' action='".$_SERVER['PHP_SELF']."?page=test' name='show_form'>";
         echo "<h3>ID=$show_data->id</h3>";
         echo "<input type='text' name='one' value='$show_data->name_f'>";
         echo "<br>";
         echo "<input type='text' name='two' value='$show_data->name_s'>";
         echo "<input type='hidden' name='sec' value='$show_data->id'>";
         echo "<br>";
         echo "<input type='submit' value='Удалить' name='delete'>";
         echo "<input type='submit' value='Редактировать' name='edit'>";
        echo "</form>";
        
        
        }
    }
register_activation_hook( __FILE__, 'my_instal');
register_deactivation_hook( __FILE__, 'my_uninstal');

add_action('admin_menu','my_admin_menu');