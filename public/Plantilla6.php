<?php // Versión 6.1

  class Plantilla {

    private $menu;
    private $submenu;
    private $submenu2;
    private $friendly;
    private $webtitle;
    private $item;
    private $subitem;
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

    /*
      MENU i SUBMENU

      Exemple:

          $menu = array(
            'start'     => "Inici",
            'metro'     => "Metro",
            'bici'      => "Bicicleta",
            'bus'       => "Autobús",
            'tren'      => "Tren",
            'contacte2' => "Contacte",
          );
          $submenu = array();
          $submenu['metro'] = array(
            'blau'     => "Línia blava",
            'groc'     => "Línia groga",
            'verd'     => "Línia verda",
            'vermell'  => "Línia vermella",
          );
          $submenu['bici'] = array(
            'bicing'   => "Bicing",
            'bicibox'  => "Bicibox",
          );
          $submenu['bus'] = array(
            'linia1'   => "Línia 1",
            'linia2'   => "Línia 2",
            'linia3'   => "Línia 3",
          );
          $submenu['tren'] = array(
            'rodalies' => "Rodalies",
            'ave'      => "Ave",
            'ffcc'     => "FF.CC.",
          );
    */
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
      $item     = $this->item;
      $subitem  = $this->subitem;

      $list = '';
      foreach($menu as $key=>$value) {
        if (isset($submenu[$key])) {
          $sublist = '';
          foreach ($submenu[$key] as $subkey=>$subvalue) {
            $subclass = ($subkey == $subitem)? ' class="subcurrent"' : '';
            $href = $this->getHRef($key, $subkey);
            $sublist .= "\t\t<li id=\"$subkey\"$subclass>".
              "<a href=\"$href\">$subvalue</a></li>\n";
          }
          $sublist = "\n\t\t<ul>\n$sublist\t\t</ul>\n\t";
        }
        else {
          $sublist = '';
        }
        $class = ($key == $item)? ' class="current"' : '';
        $href = $this->getHRef($key);
        $list .= "\t<li id=\"$key\"$class>".
          "<a href=\"$href\">$value</a>$sublist</li>\n";
      }
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
    private function getHRef($item, $subitem='') {
      if ($this->friendly) {
        if ($subitem == '') return "$item.html";
        else return $item."_".$subitem.".html";
      }
      else {
        if ($subitem == '') return "?item=$item";
        else return "?item=$item&subitem=$subitem";
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
