<?php

namespace App\Traits;

use App\Models\Translation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;

trait Translatable
{
    /**
     * Get all of the models's translations.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function translations()
    {
        return $this->morphMany(Translation::class, 'translatable');
    }

    /**
     * Get the translation attribute.
     *
     * @return \App\Models\Translation | Collection
     */
    public function getTranslationAttribute()
    {
        return $this->translation();
    }
    public function trans($field){
        return $this->translation?($this->translation['content']?$this->translation['content'][$field]: $this->$field): $this->$field;
    }
    public function translate($field, $lang){
        return $this->translation($lang)?($this->translation($lang)['content']?$this->translation($lang)['content'][$field]: $this->$field): $this->$field;
    }
    public function translation($lang=null){
        $lang = $lang?:App::getLocale();
        $default_lang = config('app.fallback_locale');
        if ($lang == $default_lang){
            $content = [];
            foreach ($this->trans_fields as $field){
                $content[$field] = $this->$field;
            }
            return new Collection(['content'=>$content]);
        }
        return $this->translations->firstWhere('language', $lang);
    }
    public function add_translations($translations){
        $trans = [];
        foreach (languages_list() as $lang){
            $content = [];
            foreach ($translations as $key => $translation){
                $content[$key] = $translation[$lang];
            }
//            die(get_class($this));
            $translation = Translation::firstOrNew(['language'=>$lang, 'translatable_type'=>get_class($this), 'translatable_id'=>$this->id],[ 'content'=>$content]);
            if($translation->exists){
                $translation->content = $content;
                $translation->save();
            }
            else {
                array_push($trans, $translation);
            }
        }
        $this->translations()->saveMany($trans);

    }
    public function getRulesAttribute(){
        $translations = [];
        foreach(languages_list() as $lang){
            foreach($this->trans_fields as $field){
                $translations["translations.".$field.'.'.$lang] = 'required';
            }
        }
        return $translations;
    }
}
