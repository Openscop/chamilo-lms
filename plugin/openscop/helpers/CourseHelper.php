<?php


class CourseHelper extends CourseManager
{
    public static function getNewCourse(){
        $limit = 6;
        $userId = api_get_user_id();

        // Getting my courses
        $my_course_list = self::get_courses_list_by_user_id($userId);

        $codeList = [];
        foreach ($my_course_list as $course) {
            $codeList[$course['real_id']] = $course['real_id'];
        }

        if (api_is_drh()) {
            $courses = self::get_courses_followed_by_drh($userId);
            foreach ($courses as $course) {
                $codeList[$course['real_id']] = $course['real_id'];
            }
        }

        $table_course_access = Database::get_main_table(TABLE_STATISTIC_TRACK_E_COURSE_ACCESS);
        $table_course = Database::get_main_table(TABLE_MAIN_COURSE);
        $table_course_url = Database::get_main_table(TABLE_MAIN_ACCESS_URL_REL_COURSE);
        $urlId = api_get_current_access_url_id();
        //$table_course_access table uses the now() and interval ...
        $now = api_get_utc_datetime();
        $sql = "SELECT u.c_id, visibility
                FROM $table_course c
                INNER JOIN $table_course_url u
                ON u.c_id = c.id
                WHERE
                    creation_date <= '$now' AND
                    creation_date > DATE_SUB('$now', INTERVAL 30 DAY) AND
                    visibility <> ".COURSE_VISIBILITY_CLOSED." AND
                    visibility <> ".COURSE_VISIBILITY_HIDDEN."
                GROUP BY u.c_id
                ORDER BY creation_date DESC
                LIMIT $limit
            ";

        $result = Database::query($sql);
        $courses = [];
        if (Database::num_rows($result)) {
            $courses = Database::store_result($result, 'ASSOC');
            $courses = self::processLastCourse($courses, $codeList);
        }
        return $courses;
    }


    public static function processLastCourse($courses, $codeList = []){
        $hotCourses = [];
        $ajax_url = api_get_path(WEB_AJAX_PATH).'course.ajax.php?a=add_course_vote';
        $stok = Security::get_existing_token();
        $user_id = api_get_user_id();

        foreach ($courses as $courseId) {
            $course_info = api_get_course_info_by_id($courseId['c_id']);
            $courseCode = $course_info['code'];
            $categoryCode = !empty($course_info['categoryCode']) ? $course_info['categoryCode'] : "";
            $my_course = $course_info;
            $my_course['go_to_course_button'] = '';
            $my_course['register_button'] = '';

            $access_link = self::get_access_link_by_user(
                api_get_user_id(),
                $course_info,
                $codeList
            );

            $sessionId = api_get_session_id();
            $cidReq = 'cidReq='.$my_course['id'];
            $session = '&id_session='.$sessionId;
            $action = '&action=view';

            $learningPaths = Database::getManager()
                ->createQueryBuilder()
                ->select('*')
                ->from('c_lp', 'lp')
                ->where('c_id = '.$my_course['real_id'])
                ->orderBy('id', 'ASC')
                ->getDQL();
            $lp = Database::fetch_assoc(Database::query($learningPaths));

            // main/lp/lp_controller.php?cidReq=TEST&id_session=0&gidReq=0&gradebook=0&origin=&action=view&lp_id=1&isStudentView=true
            $id = $lp['id'];
            $url_start_lp = 'main/lp/lp_controller.php?'.$cidReq.$session.$action.'&lp_id='.$id;
            $url_start_lp .= '&isStudentView=true';
            $my_course['course_student_url'] = $url_start_lp;
            $my_course['course_teacher_url'] = api_get_course_url($my_course['id']);


            $userRegisteredInCourse = self::is_user_subscribed_in_course($user_id, $course_info['code']);
            $userRegisteredInCourseAsTeacher = self::is_course_teacher($user_id, $course_info['code']);
            $userRegistered = $userRegisteredInCourse && $userRegisteredInCourseAsTeacher;
            $my_course['is_course_student'] = $userRegisteredInCourse;
            $my_course['is_course_teacher'] = $userRegisteredInCourseAsTeacher;
            $my_course['is_registered'] = $userRegistered;
            $my_course['title_cut'] = cut($course_info['title'], 45);

            $percent = learnpath::getProgress($id, $user_id, $courseId, $sessionId);
            $textPercent = $percent . '%';
            $my_course['progress'] = learnpath::get_progress_bar($percent, $textPercent);

            // Course visibility
            if ($access_link && in_array('register', $access_link)) {
                $my_course['course_register_url'] = api_get_path(WEB_COURSE_PATH).$course_info['path'].'/index.php?action=subscribe&sec_token='.$stok;
                $my_course['register_button'] = Display::url(
                    get_lang('Subscribe').' '.
                    Display::returnFontAwesomeIcon('sign-in'),
                    $my_course['course_register_url'],
                    [
                        'class' => 'btn btn-success btn-sm',
                        'title' => get_lang('Subscribe'),
                        'aria-label' => get_lang('Subscribe'),
                    ]
                );
            }

            if (($access_link && in_array('enter', $access_link) ||
                $course_info['visibility'] == COURSE_VISIBILITY_OPEN_WORLD) && ($my_course['is_course_student'] || $my_course['is_course_teacher'])
            ) {
                if($my_course['is_course_teacher']){
                    $url = $my_course['course_teacher_url'];
                }else{
                    $url = $url_start_lp;
                }
                $my_course['go_to_course_button'] = Display::url(
                    get_lang('GoToCourse').' '.
                    Display::returnFontAwesomeIcon('share'),
                    $url,
                    [
                        'class' => 'btn btn-default btn-sm',
                        'title' => get_lang('GoToCourse'),
                        'aria-label' => get_lang('GoToCourse'),
                    ]
                );
            }

            if ($access_link && in_array('unsubscribe', $access_link) && $my_course['is_course_student'] && $my_course['is_course_teacher'] == false) {
                $my_course['course_unsubscribe_url'] = api_get_path(WEB_CODE_PATH).'auth/courses.php?action=unsubscribe&course_code='.$courseCode
                    .'&sec_token='.$stok.'&category_code='.$categoryCode;
                $my_course['unsubscribe_button'] = Display::url(
                    get_lang('Unreg').' '.
                    Display::returnFontAwesomeIcon('sign-out'),
                    $my_course['course_unsubscribe_url'],
                    [
                        'class' => 'btn btn-danger btn-sm',
                        'title' => get_lang('Unreg'),
                        'aria-label' => get_lang('Unreg'),
                    ]
                );
            }

            // start buycourse validation
            // display the course price and buy button if the buycourses plugin is enabled and this course is configured
            $plugin = BuyCoursesPlugin::create();
            $isThisCourseInSale = $plugin->buyCoursesForGridCatalogValidator(
                $course_info['real_id'],
                BuyCoursesPlugin::PRODUCT_TYPE_COURSE
            );
            if ($isThisCourseInSale) {
                // set the price label
                $my_course['price'] = $isThisCourseInSale['html'];
                // set the Buy button instead register.
                if ($isThisCourseInSale['verificator'] && !empty($my_course['register_button'])) {
                    $my_course['register_button'] = $plugin->returnBuyCourseButton(
                        $course_info['real_id'],
                        BuyCoursesPlugin::PRODUCT_TYPE_COURSE
                    );
                }
            }
            // end buycourse validation

            // Description
            $my_course['description_button'] = self::returnDescriptionButton($course_info);
            $my_course['teachers'] = self::getTeachersFromCourse($course_info['real_id'], true);
            $point_info = self::get_course_ranking($course_info['real_id'], 0);
            $my_course['rating_html'] = '';
            if (api_get_configuration_value('hide_course_rating') === false) {
                $my_course['rating_html'] = Display::return_rating_system(
                    'star_'.$course_info['real_id'],
                    $ajax_url.'&course_id='.$course_info['real_id'],
                    $point_info
                );
            }

            if($my_course['is_course_student']) {
                $my_course['public_url'] = $my_course['course_student_url'];
            }else if($my_course['is_course_teacher']){
                $my_course['public_url'] = $my_course['course_teacher_url'];
            }else if($my_course['register_button']){
                $my_course['public_url'] = $my_course['course_register_url'];
            }

//            print('<pre>'.print_r($my_course, true).'</pre>');
//            die;

            $hotCourses[] = $my_course;
        }

        return $hotCourses;
    }
}
