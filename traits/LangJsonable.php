<?php namespace Bedard\Shop\Traits;

use Lang;

trait LangJsonable
{
    /**
     * Get a set of language strings and convert them to JSON.
     *
     * @param  array    $keys
     * @return string
     */
    public function getLangJson($keys)
    {
        $lang = [];

        foreach ($keys as $key => $value) {
            $isFiltered = gettype($value) === 'array';

            // 'bedard.shop::lang.foo.bar'
            if (is_int($key)) {
                $lang[$value] = Lang::get($value);
            }

            // 'form@backend::lang.form' => ['cancel', 'save', 'saving']
            else {
                if (! $isFiltered) {
                    $key = $value;
                }

                $alias = explode('@', $key);
                $languageString = $alias[count($alias) - 1];

                $lang[$alias[0]] = $isFiltered
                    ? array_intersect_key(Lang::get($languageString), array_flip($value))
                    : Lang::get($languageString);
            }
        }

        return json_encode($lang);
    }
}
