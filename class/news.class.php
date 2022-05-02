<?php

class News
{
    protected   $errors = array(),
                $id,
                $author,
                $title,
                $content,
                $dateCreation,
                $dateModif;

    /**
     *  Constantes relativas aos erros encontrados durante a execução
     * 
     */
    const INVALID_AUTHOR = 1;
    const INVALID_TITLE = 2;
    const INVALID_CONTENT = 3;

    /**
     * Construtor da classe, distribui os elementos dados no parâmetro para os atributos correspondentes.
     * @param $values array com os valores a preencher
     * @return void
     */
    public function __construct($values = array()) {
        if (!empty($values)) //Se existirem valores nós hidratamos o objeto>
        {
            $this->hydrate($values);
        }
    }

    /**
     * Método para distribuir os valores aos atributos correspondentes.
     * @param $data array com os valores
     * @return void
     */
    public function hydrate($data)
    {
        foreach ($data as $key => $value)
        {
            $method = 'set' .ucfirst($key);

            if (is_callable(array($this, $method))) {
                $this->$method($value);
            }
        }
    }

    /**
     * Método que permite saber se a matéria é nova.
     * @return bool
     */
    public function isNew()
    {
        return empty($this->id);
    }

    /**
     * Método que permite saber se a matéria é válida.
     * @return bool
     */
    public function isValid()
    {
        return !(empty($this->author) || empty($this->title) || empty($this->content));
    }

    //SETTERS

    public function setId($id)
    {
        $this->id = (int) $id;
    }

    public function setAuthor($author)
    {
        if (!is_string($author) || empty($author)) {
            $this->errors[] = SELF::INVALID_AUTHOR;
        } else {
            $this->author = $author;
        }
        
    }

    public function setTitle($title)
    {
        if (!is_string($title) || empty($title)){
            $this->errors[] = SELF::INVALID_TITLE;
        } else {
            $this->title = $title;
        }
    
    }

    public function setContent($content)
    {
        if (!is_string($content) || empty($content)) {
            $this->errors[] = SELF::INVALID_CONTENT;
        } else {
            $this->content = $content;
        }
        
    }

    public function setDateCreation($dateCreation)
    {
        if (is_string($dateCreation) && preg_match('`[0-9]{2}/[0-9]{2}/[0-9]{4} às [0-9]{2}h[0-9]{2}`', $dateCreation)) {
            $this->dateCreation = $dateCreation;
        }
    }

    public function setDateModif($dateModif)
    {
        if (is_string($dateModif) && preg_match('`[0-9]{2}/[0-9]{2}/[0-9]{4} às [0-9]{2}h[0-9]{2}`', $dateModif)) {
            $this->dateModif = $dateModif;
        }
    }

    //GETTERS

    public function errors()
    {
        return $this->errors;
    }

    public function id()
    {
        return $this->id;
    }

    public function author()
    {
        return $this->author;
    }

    public function title()
    {
        return $this->title;
    }

    public function content()
    {
        return $this->content;
    }

    public function dateCreation()
    {
        return $this->dateCreation;
    }

    public function dateModif()
    {
        return $this->dateModif;
    }
}