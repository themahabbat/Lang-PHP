<?php
namespace Mahabbat;
class Lang {
   public function __construct($lang, $dir, $cookie, $cookieExpire)
   {
      $this->lang = empty($lang) ? 'default' : $this->secure($lang);
      $this->cookie = $this->secure($cookie);
      $this->cookieExpire = $cookieExpire;
      $this->dir = $this->secure($dir);
      $this->set($this->lang);
   }
   protected function secure($value){
      return htmlspecialchars(trim($value));
   }
   protected function make($lang, $values){
      preg_match_all("/(:[_a-zA-Z0-9]+)/", $lang, $matches);
      foreach($matches[0] as $match){
         $match = str_replace(':', '', $match);
         if(array_key_exists($match, $values)) $lang = str_replace(':'.$match, $values[$match], $lang);
      }
      
      $lang = preg_replace("/::/", ":", $lang, 1);
      return $lang;
   }
   public function get_current(){
      return json_decode(
         file_get_contents("./".$this->dir."/".$this->lang.".json")
      );
   }
   public function key($key, $values = []){
      $lang = $this->get_current();
      if(strpos($key, "@")){
         preg_match_all("/([a-zA-Z0-9_]+)/", $key, $matches);
         
         foreach($matches[0] as $index => $match){
            if($index==0) $key = $lang->{$match}[0];
            else {
               if(array_key_exists(0, $key)) $key = $key[0]->{$match};
               else $key = $key->{$match};
            }
         }
         if((bool) count($values)) return $this->make($key, $values);
         else return $key;
      }
      if ((bool)count($values)) return $this->make($lang->{$key}, $values);
      else return $lang->{$key};
   }
   public function set($lang){
      $lang = $this->secure($lang);
      $this->lang = $lang;
      setcookie($this->cookie, $lang, $this->cookieExpire);
   }
   public function current(){
      return $this->lang;
   }
   
}
function Lang($param){
   $available = array_key_exists('available', $param)
      ? $param['available'] : [$param['current']];
   $dir = array_key_exists('dir', $param)
      ? $param['dir'] : 'lang';
   $cookie = array_key_exists('cookie', $param)
      ? $param['cookie'] : 'LANG';
   $cookieExpire = array_key_exists('cookieExpire', $param)
      ? $param['cookieExpire'] : time()+86400*24*7;
   if( in_array($param['current'], $available) )
      return new Lang( $param['current'], $dir, $cookie, $cookieExpire );
   else
      return new Lang( $param['default'], $dir, $cookie, $cookieExpire );
}
