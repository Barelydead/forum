<?php
namespace CJ\Comment;

use \Anax\Database\ActiveRecordModel;

/**
 * A database driven model.
 */
class Tag extends ActiveRecordModel
{
    /**
     * @var string $tableName name of the database table.
     */

    protected $tableName = "f_tag";

    public $id;
    public $tag;
    public $description;


    /**
    *  Inits the object with the dabase
    */
    public function init($db)
    {
        $this->setDb($db);
    }

    /**
    *
    */
    public function getAllTags()
    {
        return $this->findAll();
    }


    /**
    * Add tags to post
    */
    public function getTagsForPost($id)
    {
        $sql = "SELECT t.tag from
        f_post2tag as p2t
        INNER JOIN f_tag as t ON
        t.id = p2t.tag
        INNER JOIN f_post as p ON
        p.id = p2t.post
        WHERE p.id = ?";

        $tags = $this->db->executeFetchAll($sql, [$id]);
        return $tags;
    }

    /**
    * get id from tag name
    */
    public function getIdFromTagName($name)
    {
        $res = $this->find("tag", $name);
        return $res->id;
    }

    public function getTagString($tagList)
    {
        $string = "";

        foreach ($tagList as $tag) {
            $string .= $tag->tag . " ";
        }
        return $string;
    }

    public function getPostCount($id)
    {
        $sql = "SELECT count(*) count FROM f_post2tag WHERE tag = ?";

        $res = $this->db->executeFetch($sql, [$id]);

        return $res;
    }

    public function getMostUsedTags($limit)
    {
        $sql = "SELECT t.*,
        count(t.tag) as usedCount
        FROM f_tag as t
        INNER JOIN f_post2tag as p2t ON
        p2t.tag = t.id
        group by t.tag
        ORDER BY usedCount DESC
        LIMIT ?";

        $res = $this->db->executeFetchAll($sql, [$limit]);

        return $res;
    }
}
