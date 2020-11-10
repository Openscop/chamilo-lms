<?php

/**
 * Class ForumHelper
 **/
class ForumHelper
{

    /**
     * Retrieve all the forums (regardless of their category)
     **/
    public static function get_forums() {

        $table_forums = Database::get_course_table(TABLE_FORUM);
        $table_item_property = Database::get_course_table(TABLE_ITEM_PROPERTY);

        $urlId = api_get_current_access_url_id();

        $sql = "SELECT item_properties.*, forum.*
            FROM $table_forums forum
            INNER JOIN $table_item_property item_properties
            INNER JOIN access_url_rel_course
            ON (
                forum.forum_id = item_properties.ref AND
                forum.c_id = item_properties.c_id AND
                forum.c_id = access_url_rel_course.c_id
            )
            WHERE
                item_properties.visibility <> 2 AND
                item_properties.tool = '".TOOL_FORUM."' AND
                access_url_rel_course.access_url_id = $urlId
            ORDER BY forum_order ASC";

        // Handling all the forum information.
        $result = Database::query($sql);

        // normalize with other similar request
        while ($row = Database::fetch_assoc($result)) {
            $forum_list[$row['forum_id']] = $row;
        }

        return $forum_list;
    }


}
