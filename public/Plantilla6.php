<?php // Versión 6.1

  class Plantilla {

    private $menu;
    private $submenu;
    private $submenu2;
    private $friendly;
    private $webtitle;
    private $item;
    private $subitem;
    private $subitem2;
    private $dircontents;

    public function __construct() {
      $this->menu = array('hamburguesa' => "Hamburguesas");
      $this->submenu = array();
      $this->submenu2 = array();
      $this->friendly = true;
      $this->webtitle = "Without title";
      $this->item = $this->getParameter('item', 'hamburguesa');
      $this->subitem = $this->getParameter('subitem');
      $this->dircontents = "contents/";
    }

    private function getParameter($name, $default='') {
      if (isset($_GET[$name])) {
        $value = $_GET[$name];
        $value = strip_tags($value);    // Elimina les etiquetes <>
        $value = substr($value, 0, 20); // Limitar la longitud a la clau més llarga
      }
      else {
        $value = $default;
      }
      return $value;
    }

    public function setMenu ($menu, $submenu, $submenu2) {
      $this->menu = $menu;
      $this->submenu = $submenu;
      $this->submenu2 = $submenu2;
    }

    /*
      URL AMIGABLE

      Si no és friendly les adreçes son així: /index.php?item=alfa&subitem=beta
      i si és friendly les adreçes son així: /alfa_beta.html

      Si es friendly llavors en el fitxer ".htaccess" incloure el següent:

        Options +Indexes
        Options +FollowSymlinks
        RewriteEngine on
        RewriteBase /dir_web
        RewriteRule ^([^/]+)_([^/]+).html$ index.php?item=$1&subitem=$2 [NC,L]
        RewriteRule ^([^/]+).html$         index.php?item=$1            [NC,L]

      Cal activar el mòdul REWRITE de l'Apache:

        a2enmod rewrite
    */
    public function setFriendly($friendly) {
      $this->friendly = $friendly;
    }

    /*
      Títol del lloc web
    */
    public function setWebTitle($webtitle) {
      $this->webtitle = $webtitle;
    }

    /*
      Subcarpeta amb els continguts
    */
    public function setDirContents($dircontents) {
      $this->dircontents = $dircontents;
    }

    /*
      Títol de la pàgina web
    */
    public function getPageTitle()
    {
      $menu     = $this->menu;
      $submenu  = $this->submenu;
      $submenu2  = $this->submenu2;
      $item     = $this->item;
      $subitem  = $this->subitem;
      $webtitle = $this->webtitle;

      $title = '';
      if (isset($submenu[$item][$subitem])) {
        $title .= $submenu[$item][$subitem] . ' - ';
      }
      if (isset($menu[$item])) {
        $title .= $menu[$item] . ' - ';
      }
      return "$title $webtitle";
    }

    /*
      Classe del <body>
    */
    public function getClassBody() {
      $item     = $this->item;
      $subitem  = $this->subitem;

      $classe = "$item $subitem";
      return $classe;
    }

    /*
      El menú com llistes <ul> anidades
    */
    public function getMenu()
    {
      $menu     = $this->menu;
      $submenu  = $this->submenu;
      $submenu2  = $this->submenu2;
      $item     = $this->item;
      $subitem  = $this->subitem;
      $subitem2  = $this->subitem2;

      $list = '';
      foreach($menu as $key=>$value) { // a) recorre menu principal
        if (isset($submenu[$key])) {    //b) Pregunta si menu principal tiene submenu
          $sublist = '';
          foreach ($submenu[$key] as $subkey=>$subvalue) { //c) recorre sub  menu
            
            if (isset($submenu2[$subkey])) {    //d) Pregunta si submenu tiene submenu2
              $sublist2 = '';
              foreach ($submenu2[$subkey] as $sub2key=>$sub2value) { //e) recorre sub  menu2
                
                $subclass2 = ($sub2key == $subitem2)? ' class="subcurrent"' : '';
                $href = $this->getHRef($key, $subkey, $sub2key);               
                $sublist2 .= "\t\t<li id=\"$sub2key\"$subclass2>".
                  "<a href=\"$href\">$sub2value</a></li>\n";
              }
              $sublist2 = "\n\t\t<ol>\n$sublist2\t\t</ol>\n\t";
            }
            else {  //else d)
              $sublist2 = '';
            } //end d)
                      
            $subclass = ($subkey == $subitem)? ' class="subcurrent"' : '';
            $href = $this->getHRef($key, $subkey);
            $sublist .= "\t\t<li id=\"$subkey\"$subclass>".
              "<a href=\"$href\">$subvalue</a>$sublist2</li>\n";
                   
            }//end for c)
          $sublist = "\n\t\t<ul>\n$sublist\t\t</ul>\n\t";
        }
        else {
          $sublist = '';
        } //end b)
        
        $class = ($key == $item)? ' class="current"' : '';
        $href = $this->getHRef($key);
        $list .= "\t<li id=\"$key\"$class>".
          "<a href=\"$href\">$value</a>$sublist</li>\n";
      } //end a)
      return "\n\t<ul>\n$list\t</ul>\n";
    }

    /*
      Escriure el contingut de la pàgina
    */
    public function writePageContent($dir=null, $warnings=true) {
      $item     = $this->item;
      $subitem  = $this->subitem;
      $dircontents = $dir===null? $this->dircontents: $dir;

      if (empty($subitem)) $file = $dircontents.$item.".php";
      else $file = $dircontents.$item."_".$subitem.".php";

      if (file_exists($file)) include $file;
      else if ($warnings) echo '<p class="error">Missing file: <code>'.$file.'</code></p>';
    }

    /*
      Obté les molles de pa; la ruta d'on estem
    */
    public function getBreadcrumbs($separator=' &gt; ')
    {
      $menu     = $this->menu;
      $submenu  = $this->submenu;
      $item     = $this->item;
      $subitem  = $this->subitem;
      $list = array();

      if ($subitem != '') {
        $list[] = $this->getTitle($item, $subitem);
      }
      if ($item != 'start') {
        if (count($list) == 0) $list[] = $this->getTitle($item);
        else $list[] = $this->getTagA($item);
      }
      if (count($list) == 0) $list[] = $this->getTitle('start');
      else $list[] = $this->getTagA('start');

      return implode($separator, array_reverse($list));
    }


    private function getTagA($item, $subitem='') {
      $href = $this->getHRef($item, $subitem);
      $title = $this->getTitle($item, $subitem);
      return "<a href=\"$href\">$title</a>";
    }
    
    private function getHRef($item, $subitem='', $subitem2='') {
      if ($this->friendly) {
        if ($subitem == '') return "$item.html";
        else 
        {
          if ($subitem2 == '') return $item."_".$subitem.".html";
          else return $item."_".$subitem."_".$subitem2.".html";
        }
      }
      else {
        if ($subitem == '') return "?item=$item";
        else {
          if ($subitem2 == '') return "?item=$item&subitem=$subitem";
          else {return "?item=$item&subitem=$subitem&subitem2=$subitem2";}
        }
      }
    }
    
    private function getTitle($item, $subitem='') {
      if ($subitem == '') {
        if (isset($this->menu[$item])) return $this->menu[$item];
        else return $item;
      }
      else {
        if (isset($this->submenu[$item][$subitem])) return $this->submenu[$item][$subitem];
        else return $subitem;
      }
    }

} // class Plantilla

?>
