<?php namespace MicheleAngioni\Support\Cache;

class KeyManager implements KeyManagerInterface {

    /**
     * Return Cache key generated by using $id and $with array,
     * as well $section and $modelClass inputs.
     *
     * @param  string|bool  $id
     * @param  array        $array
     * @param  string       $section
     * @param  string       $modelClass
     *
     * @return string
     */
    public function getKey($id = false, array $array = array(), $section, $modelClass = __CLASS__)
    {
        $string = $this->getString($id, $array, $section, $modelClass);

        return md5($string);
    }

    /**
     * Return the ready-to-be-encrypted-string key generated by using $id and $with array,
     * as well $section and $modelClass inputs.
     *
     * @param  string|bool  $id
     * @param  array        $array
     * @param  string       $section
     * @param  string       $modelClass
     *
     * @return string
     */
    protected function getString($id = false, array $array = array(), $section, $modelClass = __CLASS__)
    {
        $string = $section.$modelClass;

        if($id) {
            $string = $string.'id'.$id;
        }

        if($array) {
            foreach($array as $key => $value) {
                $string = $string.$key;
            }
        }

        return $string;
    }

    /**
     * Return Cache tags generated by using $id and $with array, as well $section and $modelClass inputs.
     *
     * @param  bool|int  $id
     * @param  array     $array
     * @param  string    $section
     * @param  string    $modelClass
     *
     * @return array
     */
    public function getTags($id = false, array $array = array(), $section, $modelClass = __CLASS__)
    {
        $tags = [$section, $modelClass];

        if($id) {
            $tags[] = $modelClass.'id'.$id;
        }

        if($array) {
            foreach( $array as $relation ) {
                $tags[] = $relation;
            }
        }

        return $tags;
    }

    /**
     * Return a method-customized Cache key generated by using $id and $with array,
     * as well $section and $modelClass inputs.
     * If not customName is provided, the name of the calling method will be used.
     *
     * @param  string|bool  $customName
     * @param  string|bool  $id
     * @param  array        $array
     * @param  string       $section
     * @param  string       $modelClass
     *
     * @return string
     */
    public function getCustomMethodKey($customName = false, $id = false, array $array = array(), $section, $modelClass = __CLASS__)
    {
        if(!$customName) {
            $customName=debug_backtrace()[2]['function'];
        }

        $string = $this->getString($id, $array, $section, $modelClass).$modelClass.$customName;

        return md5($string);
    }

    /**
     * Return Cache tags generated by using $id and $with array, as well $section and $modelClass inputs.
     * It has an additional method-customized tag.
     * If not customName is provided, the name of the calling method will be used.
     *
     * @param  string|bool  $customName
     * @param  string|bool  $id
     * @param  array        $array
     * @param  string       $section
     * @param  string       $modelClass
     *
     * @return array
     */
    public function getCustomMethodTags($customName = false, $id = false, array $array = array(), $section, $modelClass = __CLASS__)
    {
        if(!$customName) {
            $customName=debug_backtrace()[2]['function'];
        }

        $tags = $this->getTags($id, $array, $section, $modelClass);
        $tags[] = $modelClass.$customName;

        return $tags;
    }

}
