<?php

class NewsManager
{
    /**
     * Atributo que contém a instância da representação da BDD.
     * @var PDO
     */
    protected $db;

    /**
     * Construtor encarregado de atribuir a instância da PDO.
     * @param PDO $db
     * @return void
     */
    public function __construct(PDO $db) {
        $this->db = $db;
    }

    /**
     * Método para adicionar uma matéria.
     * @param News $news A matéria adicionada
     * @return void
     */
    protected function add(News $news)
    {
        $request = $this->db->prepare('INSERT INTO news 
                                    SET author = :author, 
                                    title = :title, 
                                    content = :content, 
                                    dateCreation = NOW(), 
                                    dateModif = NOW()');
        $request->bindValue('title', $news->title());
        $request->bindValue('author', $news->author());
        $request->bindValue('content', $news->content());

        $request->execute();

    }

    /**
     * método que retorna o número total de matérias.
     * @return int
     */
    public function count()
    {
        return $this->db->query('SELECT COUNT(*) FROM news')->fetchColumn();
    }

    /**
     * Método para apagar uma matéria.
     * @param int $id O identificador da matéria a apagar
     * @return void
     */
    public function delete($id)
    {
        $this->db->exec('DELETE FROM news WHERE id = '.(int)$id);    
    }

    /**
     * Método que retorna uma lista de matérias.
     * @param int $first A primeira matéria a selecionar
     * @param int $limit A quantidade de matérias a selecionar
     * @return array Uma lista onde cada elemento é uma instância de News
     */
    public function getList($first = -1, $limit = -1)
    {
        $sql = 'SELECT id, author, title, content, 
                DATE_FORMAT(dateCreation, \'%d/%m/%Y às %Hh%i\') AS dateCreated, 
                DATE_FORMAT(dateModif, \'%d/%m/%Y às %Hh%i\') AS dateModif
                FROM news ORDER BY id DESC';
        
        if ($first != -1 || $limit != -1) {
            $sql .= 'LIMIT' .(int) $limit.' OFFSET '.(int) $first;
        }
        
        $request = $this->db->query($sql);
        $request->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'News');

        $newsList = $request->fetchAll();

        $request->closeCursor();

        return $newsList;
    }

    /**
     * Método que retorna uma matéria específica
     * @param int $id O id da matéria escolhida
     * @return News A matéria pedida
     */
    public function getSingle($id)
    {
        $request = $this->db->prepare('SELECT id, author, title, content, 
                                        DATE_FORMAT(dateCreation, \'%d/%m/%Y às %Hh%i\') AS dateCreation, 
                                        DATE_FORMAT(dateModif, \'%d/%m/%Y às %Hh%i\') AS dateModif 
                                        FROM news WHERE id = :id');

        $request->bindValue('id', (int) $id, PDO::PARAM_INT);
        $request->execute();

        $request->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'News');

        return $request->fetch();

    }

    /**
     * Método para salvar uma matéria.
     * @param News $news A matéria a ser salva
     * @see self::add()
     * @see self::update()
     * @return void
     */
    public function save(News $news)
    {
        if ($news->isValid()) {
            $news->isNew() ? $this->add($news) : $this->update($news);
        } else {
            throw new RuntimeException('The news must be valid to be enrigestered');
        }
        
    }

    /**
     * Método para modificar uma matéria existente
     * @param News $news A matéria a modificar
     * @return void
     */
    public function update(News $news)
    {
        $request = $this->db->prepare(
                                    'UPDATE news 
                                    SET author = :author, title = :title, content = :content, dateModif = NOW()
                                    WHERE id = :id');

        $request->bindValue('title', $news->title());
        $request->bindValue('author', $news->author());
        $request->bindValue('content', $news->content());
        $request->bindValue('id', $news->id(), PDO::PARAM_INT);
        
        $request->execute();
    }
}
