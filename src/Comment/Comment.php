<?php
namespace CJ\Comment;

use \Anax\Database\ActiveRecordModel;

/**
 * A database driven model.
 */
class Comment extends ActiveRecordModel
{
    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "f_post";

    /**
     * Columns in the table.
     *
     * @var integer $id primary key auto incremented.
     */
    public $id;
    public $content;
    public $title;
    public $likes;

    public $user;
    public $question;
    public $reply;
    public $comment;
    public $questionId;
    public $replyId;

    public $created;
    public $deleted;
    public $updated;


    /**
    *  Inits the object with the dabase
    */
    public function init($db)
    {
        $this->setDb($db);
    }

    /**
     * Gets all comments from Db
     */
    public function getComments()
    {
        return $this->findAll();
    }

    /**
     * Gets all comments from Db with attached user and tag info
     */
    public function getCommentsWithUserInfo($order = "CREATED", $limit = 999)
    {
        $sql = "SELECT * FROM VPostOverview GROUP BY title ORDER BY $order DESC LIMIT $limit";

        return $this->db->executeFetchAll($sql);
    }


    /**
     * Gets all comments from Db
     */
    public function getComment($index)
    {
        return $this->find("id", $index);
    }


    /**
     * Delete comment with a specific ID
     */
    public function deleteComment($index)
    {
        $this->find("id", $index);
        $this->delete();
    }

    /**
     * Get all replies to a question
     * @param int id of original post
     * @return array
     */
    public function getReplies($id, $sort, $order)
    {
        $sql = "SELECT p.*,
        p.id as postId,
        u.mail as mail,
        u.username as username
        FROM f_post AS p
        INNER JOIN f_user AS u ON
        u.id = p.user
        WHERE p.questionId = ? AND
        p.reply = 1
        ORDER BY p.$sort $order";

        $replies = $this->db->executeFetchAll($sql, [$id]);
        return isset($replies) ? $replies : [];
    }

    /**
     * Get all comments with a certain questionId
     * @param int id of original post
     * @return array
     */
    public function getReplyComments($id)
    {
        $sql = "SELECT p.*, u.*,
        p.id AS postId
        FROM f_post AS p
        INNER JOIN f_user AS u ON
        u.id = p.user
        WHERE p.questionId = ? AND
        p.comment = 1";

        $replies = $this->db->executeFetchAll($sql, [$id]);
        return isset($replies) ? $replies : [];
    }


    /**
     *
     */
    public function getCommentsWithTagName($name)
    {
        $sql = "SELECT * FROM VPostOverview
        WHERE tag = ?";

        $comments = $this->db->executeFetchAll($sql, [$name]);
        return isset($comments) ? $comments : [];
    }


    /**
     *
     */
    public function getAllPostsForUser($userId)
    {
        $comments = $this->findAllWhere("user = ?", $userId);
        return isset($comments) ? $comments : [];
    }


    /**
     *
     */
    public function vote($id, $user, $type)
    {
        $amount = ($type == "upvote") ? 1 : -1;
        $sql = "SELECT * FROM f_like2user WHERE user = ? AND post = ?";
        $res = $this->db->executeFetch($sql, [$user, $id]);

        // IF ALREADY VOTED THE SAME BEFORE
        if ($res && $res->type == $type) {
            return;
        }

        // IF ALREADY VOTED BUT OTHER TYPE
        if ($res && $res->type !== $type) {
            $this->find("id", $id);
            $this->likes += $amount;
            $this->save();

            $sql = "UPDATE f_like2user SET
            type = ? WHERE user = ? AND post = ?";
            $this->db->execute($sql, [$type, $user, $id]);
            return;
        }

        // IF NEVER VOTED
        $this->find("id", $id);
        $this->likes += $amount;
        $this->save();

        $sql = "INSERT INTO f_like2user(user, post, type) VALUES
        (?, ?, ?)";
        $this->db->execute($sql, [$user, $id, $type]);
    }

    /**
     * Get number of answers for a specific post
     */
    public function countAnswers($id)
    {
        return count($this->findAllWhere("questionId = ?", $id));
    }

    /**
     * Mark comment as accepted
     */
    public function toggleMarkAnswer($id)
    {
        $this->find("id", $id);

        if ($this->accepted == 1) {
            $this->accepted = 0;
        } else {
            $this->accepted = 1;
        }
        $this->save();
    }


    /**
     * @return string
     * Get the avatar HTML
     */
    public function getAvatar($index, $classes = "", $size = 125)
    {
        $this->find("id", $index);
        $hash = md5($this->userMail);

        $html = "<img src='https://www.gravatar.com/avatar/$hash?s=$size&default=mm' class='$classes'>";
        return $html;
    }
}
