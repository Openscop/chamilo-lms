<?php


class HomeController extends IndexManager
{
    /**
     * retourne la liste des courses par ordre de creation (la plus recente avant)
     * @return array
     */
    public function getHomeCourseList(){
        return CourseHelper::getHomeCourses();
    }

    /**
     * retourne la liste des courses de l'user par ordre de creation (la plus recente avant)
     * @return array
     */
    public function getUserCourseList(){
        return CourseHelper::getUserCourses();
    }


    public function display_hot_course_item()
    {
        $tpl = $this->tpl->get_template('layout/hot_course_item.tpl');
        $this->tpl->display($tpl);
    }

    public function returnCoursesAndSessionsViewBySession($user_id)
    {

        $sessionCount = 0;
        $courseCount = 0;
        $load_history = (isset($_GET['history']) && intval($_GET['history']) == 1) ? true : false;

        if ($load_history) {
            // Load sessions in category in *history*
            $session_categories = UserManager::get_sessions_by_category($user_id, true);
        } else {
            // Load sessions in category
            $session_categories = UserManager::get_sessions_by_category($user_id, false);
        }

        $html = '';
        $loadDirs = $this->load_directories_preview;

        // If we're not in the history view...
        $listCoursesInfo = [];
        if (!isset($_GET['history'])) {
            // Display special courses
            $specialCoursesResult = CourseManager::returnSpecialCourses(
                $user_id,
                $loadDirs
            );
            $specialCourses = $specialCoursesResult;

            if ($specialCourses) {
                $this->tpl->assign('courses', $specialCourses);
                $html = $this->tpl->fetch(
                    $this->tpl->get_template('/user_portal/classic_courses_without_category.tpl')
                );
            }

            // Display courses
            // [code=>xxx, real_id=>000]
            $listCourses = CourseManager::get_courses_list_by_user_id(
                $user_id,
                false
            );

            foreach ($listCourses as $i => $listCourseCodeId) {
                if (isset($listCourseCodeId['special_course'])) {
                    continue;
                }
                $courseCategory = CourseManager::getUserCourseCategoryForCourse(
                    $user_id,
                    $listCourseCodeId['real_id']
                );

                $userCatTitle = '';
                $userCategoryId = 0;
                if ($courseCategory) {
                    $userCategoryId = $courseCategory['user_course_cat'];
                    $userCatTitle = $courseCategory['title'];
                }

                $listCourse = api_get_course_info_by_id($listCourseCodeId['real_id']);
                $listCoursesInfo[] = [
                    'course' => $listCourse,
                    'code' => $listCourseCodeId['code'],
                    'id' => $listCourseCodeId['real_id'],
                    'title' => $listCourse['title'],
                    'userCatId' => $userCategoryId,
                    'userCatTitle' => $userCatTitle,
                ];
                $courseCount++;
            }
            usort($listCoursesInfo, 'self::compareByCourse');
        }

        $listCoursesInSession = [];
        if (is_array($session_categories)) {
            // all courses that are in a session
            $listCoursesInSession = SessionManager::getNamedSessionCourseForCoach($user_id);
        }

        // we got all courses
        // for each user category, sorted alphabetically, display courses
        $listUserCategories = CourseManager::get_user_course_categories($user_id);
        $listCoursesAlreadyDisplayed = [];
        uasort($listUserCategories, "self::compareListUserCategory");
        $listUserCategories[0] = '';

        $html .= '<div class="session-view-block">';
        foreach ($listUserCategories as $userCategoryId => $userCat) {
            // add user category
            $userCategoryHtml = '';
            if ($userCategoryId != 0) {
                $userCategoryHtml = '<div class="session-view-well ">';
                $userCategoryHtml .= self::getHtmlForUserCategory($userCategoryId, $userCat['title']);
            }
            // look for course in this userCat in session courses : $listCoursesInSession
            $htmlCategory = '';
            if (isset($listCoursesInSession[$userCategoryId])) {
                // list of courses in this user cat
                foreach ($listCoursesInSession[$userCategoryId]['courseInUserCatList'] as $i => $listCourse) {
                    // add course
                    $listCoursesAlreadyDisplayed[$listCourse['courseId']] = 1;
                    if ($userCategoryId == 0) {
                        $htmlCategory .= '<div class="panel panel-default">';
                    } else {
                        $htmlCategory .= '<div class="panel panel-default">';
                    }
                    $htmlCategory .= '<div class="panel-body">';
                    $coursesInfo = $listCourse['course'];

                    $htmlCategory .= self::getHtmlForCourse(
                        $coursesInfo,
                        $userCategoryId,
                        1,
                        $loadDirs
                    );
                    // list of session category
                    $htmlSessionCategory = '<div
                        class="session-view-row"
                        style="display:none;"
                        id="courseblock-'.$coursesInfo['real_id'].'"
                        >';
                    foreach ($listCourse['sessionCatList'] as $listCategorySession) {
                        $catSessionId = null;
                        if (isset($listCategorySession['catSessionId'])) {
                            $catSessionId = $listCategorySession['catSessionId'];
                        }
                        // add session category
                        if ($catSessionId) {
                            $htmlSessionCategory .= self::getHtmlSessionCategory(
                                $listCategorySession['catSessionId'],
                                $listCategorySession['catSessionName']
                            );
                        }

                        // list of session
                        $htmlSession = ''; // start
                        foreach ($listCategorySession['sessionList'] as $listSession) {
                            // add session
                            $htmlSession .= '<div class="session-view-row">';
                            $htmlSession .= self::getHtmlForSession(
                                $listSession['sessionId'],
                                $listSession['sessionName'],
                                $catSessionId,
                                $coursesInfo
                            );
                            $htmlSession .= '</div>';
                            $sessionCount++;
                        }
                        $htmlSession .= ''; // end session block
                        $htmlSessionCategory .= $htmlSession;
                    }
                    $htmlSessionCategory .= '</div>'; // end session cat block
                    $htmlCategory .= $htmlSessionCategory.'</div></div>';
                    $htmlCategory .= ''; // end course block
                }
                $userCategoryHtml .= $htmlCategory;
            }

            // look for courses in this userCat in not in session courses : $listCoursesInfo
            // if course not already added
            $htmlCategory = '';
            foreach ($listCoursesInfo as $i => $listCourse) {
                if ($listCourse['userCatId'] == $userCategoryId &&
                    !isset($listCoursesAlreadyDisplayed[$listCourse['id']])
                ) {
                    if ($userCategoryId != 0) {
                        $htmlCategory .= '<div class="panel panel-default">';
                    } else {
                        $htmlCategory .= '<div class="panel panel-default">';
                    }

                    $htmlCategory .= '<div class="panel-body">';
                    $htmlCategory .= self::getHtmlForCourse(
                        $listCourse['course'],
                        $userCategoryId,
                        0,
                        $loadDirs
                    );
                    $htmlCategory .= '</div></div>';
                }
            }
            $htmlCategory .= '';
            $userCategoryHtml .= $htmlCategory; // end user cat block
            if ($userCategoryId != 0) {
                $userCategoryHtml .= '</div>';
            }
            $html .= $userCategoryHtml;
        }
        $html .= '</div>';

        return [
            'html' => '',
            'sessions' => $session_categories,
            'courses' => $listCoursesInfo,
            'session_count' => $sessionCount,
            'course_count' => $courseCount,
        ];
        return [
            'html' => $html,
            'sessions' => $session_categories,
            'courses' => $listCoursesInfo,
            'session_count' => $sessionCount,
            'course_count' => $courseCount,
        ];
    }

    public function returnCoursesAndSessions($user_id, $showSessions = true, $categoryCodeFilter = '', $useUserLanguageFilterIfAvailable = true, $loadHistory = false)
    {
        $gameModeIsActive = api_get_setting('gamification_mode');
        $viewGridCourses = api_get_configuration_value('view_grid_courses');
        $showSimpleSessionInfo = api_get_configuration_value('show_simple_session_info');
        $coursesWithoutCategoryTemplate = '/user_portal/classic_courses_without_category.tpl';
        $coursesWithCategoryTemplate = '/user_portal/classic_courses_with_category.tpl';
        $showAllSessions = api_get_configuration_value('show_all_sessions_on_my_course_page') === true;

        if ($loadHistory) {
            // Load sessions in category in *history*
            $session_categories = UserManager::get_sessions_by_category($user_id, true);
        } else {
            // Load sessions in category
            $session_categories = UserManager::get_sessions_by_category($user_id, false);
        }

        $sessionCount = 0;
        $courseCount = 0;

        // Student info code check (shows student progress information on
        // courses list
        $studentInfo = api_get_configuration_value('course_student_info');

        $studentInfoProgress = !empty($studentInfo['progress']) && $studentInfo['progress'] === true;
        $studentInfoScore = !empty($studentInfo['score']) && $studentInfo['score'] === true;
        $studentInfoCertificate = !empty($studentInfo['certificate']) && $studentInfo['certificate'] === true;
        $courseCompleteList = [];
        $coursesInCategoryCount = 0;
        $coursesNotInCategoryCount = 0;
        $listCourse = '';
        $specialCourseList = '';

        // If we're not in the history view...
        if ($loadHistory === false) {
            // Display special courses.
            $specialCourses = CourseManager::returnSpecialCourses(
                $user_id,
                $this->load_directories_preview,
                $useUserLanguageFilterIfAvailable
            );

            // Display courses.
            $courses = CourseManager::returnCourses(
                $user_id,
                $this->load_directories_preview,
                $useUserLanguageFilterIfAvailable
            );

            // Course option (show student progress)
            // This code will add new variables (Progress, Score, Certificate)
            if ($studentInfoProgress || $studentInfoScore || $studentInfoCertificate) {
                if (!empty($specialCourses)) {
                    foreach ($specialCourses as $key => $specialCourseInfo) {
                        if ($studentInfoProgress) {
                            $progress = Tracking::get_avg_student_progress(
                                $user_id,
                                $specialCourseInfo['course_code']
                            );
                            $specialCourses[$key]['student_info']['progress'] = $progress === false ? null : $progress;
                        }

                        if ($studentInfoScore) {
                            $percentage_score = Tracking::get_avg_student_score(
                                $user_id,
                                $specialCourseInfo['course_code'],
                                []
                            );
                            $specialCourses[$key]['student_info']['score'] = $percentage_score;
                        }

                        if ($studentInfoCertificate) {
                            $category = Category::load(
                                null,
                                null,
                                $specialCourseInfo['course_code'],
                                null,
                                null,
                                null
                            );
                            $specialCourses[$key]['student_info']['certificate'] = null;
                            if (isset($category[0])) {
                                if ($category[0]->is_certificate_available($user_id)) {
                                    $specialCourses[$key]['student_info']['certificate'] = Display::label(
                                        get_lang('Yes'),
                                        'success'
                                    );
                                } else {
                                    $specialCourses[$key]['student_info']['certificate'] = Display::label(
                                        get_lang('No'),
                                        'danger'
                                    );
                                }
                            }
                        }
                    }
                }

                if (isset($courses['in_category'])) {
                    foreach ($courses['in_category'] as $key1 => $value) {
                        if (isset($courses['in_category'][$key1]['courses'])) {
                            foreach ($courses['in_category'][$key1]['courses'] as $key2 => $courseInCatInfo) {
                                $courseCode = $courseInCatInfo['course_code'];
                                if ($studentInfoProgress) {
                                    $progress = Tracking::get_avg_student_progress(
                                        $user_id,
                                        $courseCode
                                    );
                                    $courses['in_category'][$key1]['courses'][$key2]['student_info']['progress'] = $progress === false ? null : $progress;
                                }

                                if ($studentInfoScore) {
                                    $percentage_score = Tracking::get_avg_student_score(
                                        $user_id,
                                        $courseCode,
                                        []
                                    );
                                    $courses['in_category'][$key1]['courses'][$key2]['student_info']['score'] = $percentage_score;
                                }

                                if ($studentInfoCertificate) {
                                    $category = Category::load(
                                        null,
                                        null,
                                        $courseCode,
                                        null,
                                        null,
                                        null
                                    );
                                    $courses['in_category'][$key1]['student_info']['certificate'] = null;
                                    $isCertificateAvailable = $category[0]->is_certificate_available($user_id);
                                    if (isset($category[0])) {
                                        if ($viewGridCourses) {
                                            if ($isCertificateAvailable) {
                                                $courses['in_category'][$key1]['student_info']['certificate'] = get_lang(
                                                    'Yes'
                                                );
                                            } else {
                                                $courses['in_category'][$key1]['student_info']['certificate'] = get_lang(
                                                    'No'
                                                );
                                            }
                                        } else {
                                            if ($isCertificateAvailable) {
                                                $courses['in_category'][$key1]['student_info']['certificate'] = Display::label(
                                                    get_lang('Yes'),
                                                    'success'
                                                );
                                            } else {
                                                $courses['in_category'][$key1]['student_info']['certificate'] = Display::label(
                                                    get_lang('No'),
                                                    'danger'
                                                );
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                if (isset($courses['not_category'])) {
                    foreach ($courses['not_category'] as $key => $courseNotInCatInfo) {
                        $courseCode = $courseNotInCatInfo['course_code'];
                        if ($studentInfoProgress) {
                            $progress = Tracking::get_avg_student_progress(
                                $user_id,
                                $courseCode
                            );
                            $courses['not_category'][$key]['student_info']['progress'] = $progress === false ? null : $progress;
                        }

                        if ($studentInfoScore) {
                            $percentage_score = Tracking::get_avg_student_score(
                                $user_id,
                                $courseCode,
                                []
                            );
                            $learningPaths = Database::getManager()
                                ->createQueryBuilder()
                                ->select('*')
                                ->from('c_lp', 'lp')
                                ->where('c_id = '.$courseNotInCatInfo['real_id'])
                                ->orderBy('id', 'ASC')
                                ->getDQL();
                            $lp = Database::fetch_assoc(Database::query($learningPaths));
                            $id = $lp['id'];

                            $sessionId = api_get_session_id();
                            $percent = learnpath::getProgress($id, $user_id, $courseNotInCatInfo['real_id'], $sessionId);
                            $textPercent = $percent . '%';
                            $courses['not_category'][$key]['student_info']['progress'] = learnpath::get_progress_bar($percent, $textPercent);

                            $courses['not_category'][$key]['student_info']['score'] = $percentage_score;
                        }

                        if ($studentInfoCertificate) {
                            $category = Category::load(
                                null,
                                null,
                                $courseCode,
                                null,
                                null,
                                null
                            );
                            $courses['not_category'][$key]['student_info']['certificate'] = null;

                            if (isset($category[0])) {
                                $certificateAvailable = $category[0]->is_certificate_available($user_id);
                                if ($viewGridCourses) {
                                    if ($certificateAvailable) {
                                        $courses['not_category'][$key]['student_info']['certificate'] = get_lang('Yes');
                                    } else {
                                        $courses['not_category'][$key]['student_info']['certificate'] = get_lang('No');
                                    }
                                } else {
                                    if ($certificateAvailable) {
                                        $courses['not_category'][$key]['student_info']['certificate'] = Display::label(
                                            get_lang('Yes'),
                                            'success'
                                        );
                                    } else {
                                        $courses['not_category'][$key]['student_info']['certificate'] = Display::label(
                                            get_lang('No'),
                                            'danger'
                                        );
                                    }
                                }
                            }
                        }
                    }
                }
            }

            if ($viewGridCourses) {
                $coursesWithoutCategoryTemplate = '/user_portal/grid_courses_without_category.tpl';
                $coursesWithCategoryTemplate = '/user_portal/grid_courses_with_category.tpl';
            }

            if ($specialCourses) {
                if ($categoryCodeFilter) {
                    $specialCourses = self::filterByCategory($specialCourses, $categoryCodeFilter);
                }
                $this->tpl->assign('courses', $specialCourses);
                $specialCourseList = $this->tpl->fetch($this->tpl->get_template($coursesWithoutCategoryTemplate));
                $courseCompleteList = array_merge($courseCompleteList, $specialCourses);
            }

            if ($courses['in_category'] || $courses['not_category']) {
                foreach ($courses['in_category'] as $courseData) {
                    if (!empty($courseData['courses'])) {
                        $coursesInCategoryCount += count($courseData['courses']);
                        $courseCompleteList = array_merge($courseCompleteList, $courseData['courses']);
                    }
                }

                $coursesNotInCategoryCount += count($courses['not_category']);
                $courseCompleteList = array_merge($courseCompleteList, $courses['not_category']);

                if ($categoryCodeFilter) {
                    $courses['in_category'] = self::filterByCategory(
                        $courses['in_category'],
                        $categoryCodeFilter
                    );
                    $courses['not_category'] = self::filterByCategory(
                        $courses['not_category'],
                        $categoryCodeFilter
                    );
                }

                $this->tpl->assign('courses', $courses['not_category']);
                $this->tpl->assign('categories', $courses['in_category']);

                $listCourse = $this->tpl->fetch($this->tpl->get_template($coursesWithCategoryTemplate));
                $listCourse .= $this->tpl->fetch($this->tpl->get_template($coursesWithoutCategoryTemplate));
            }

            $courseCount = count($specialCourses) + $coursesInCategoryCount + $coursesNotInCategoryCount;
        }

        $sessions_with_category = '';
        $sessions_with_no_category = '';
        $collapsable = api_get_configuration_value('allow_user_session_collapsable');
        $collapsableLink = '';
        if ($collapsable) {
            $collapsableLink = api_get_path(WEB_PATH).'user_portal.php?action=collapse_session';
        }

        $extraFieldValue = new ExtraFieldValue('session');
        if ($showSessions) {
            $coursesListSessionStyle = api_get_configuration_value('courses_list_session_title_link');
            $coursesListSessionStyle = $coursesListSessionStyle === false ? 1 : $coursesListSessionStyle;
            if (api_is_drh()) {
                $coursesListSessionStyle = 1;
            }

            $portalShowDescription = api_get_setting('show_session_description') === 'true';

            // Declared listSession variable
            $listSession = [];
            // Get timestamp in UTC to compare to DB values (in UTC by convention)
            $session_now = strtotime(api_get_utc_datetime(time()));
            if (is_array($session_categories)) {
                foreach ($session_categories as $session_category) {
                    $session_category_id = $session_category['session_category']['id'];
                    // Sessions and courses that are not in a session category
                    if (empty($session_category_id) &&
                        isset($session_category['sessions'])
                    ) {
                        // Independent sessions
                        foreach ($session_category['sessions'] as $session) {
                            $session_id = $session['session_id'];

                            // Don't show empty sessions.
                            if (count($session['courses']) < 1) {
                                continue;
                            }

                            // Courses inside the current session.
                            $date_session_start = $session['access_start_date'];
                            $date_session_end = $session['access_end_date'];
                            $coachAccessStartDate = $session['coach_access_start_date'];
                            $coachAccessEndDate = $session['coach_access_end_date'];
                            $count_courses_session = 0;

                            // Loop course content
                            $html_courses_session = [];
                            $atLeastOneCourseIsVisible = false;
                            $markAsOld = false;
                            $markAsFuture = false;

                            foreach ($session['courses'] as $course) {
                                $is_coach_course = api_is_coach($session_id, $course['real_id']);
                                $allowed_time = 0;
                                $allowedEndTime = true;

                                if (!empty($date_session_start)) {
                                    if ($is_coach_course) {
                                        $allowed_time = api_strtotime($coachAccessStartDate);
                                    } else {
                                        $allowed_time = api_strtotime($date_session_start);
                                    }

                                    $endSessionToTms = null;
                                    if (!isset($_GET['history'])) {
                                        if (!empty($date_session_end)) {
                                            if ($is_coach_course) {
                                                // if coach end date is empty we use the default end date
                                                if (empty($coachAccessEndDate)) {
                                                    $endSessionToTms = api_strtotime($date_session_end);
                                                    if ($session_now > $endSessionToTms) {
                                                        $allowedEndTime = false;
                                                    }
                                                } else {
                                                    $endSessionToTms = api_strtotime($coachAccessEndDate);
                                                    if ($session_now > $endSessionToTms) {
                                                        $allowedEndTime = false;
                                                    }
                                                }
                                            } else {
                                                $endSessionToTms = api_strtotime($date_session_end);
                                                if ($session_now > $endSessionToTms) {
                                                    $allowedEndTime = false;
                                                }
                                            }
                                        }
                                    }
                                }

                                if ($showAllSessions) {
                                    if ($allowed_time < $session_now && $allowedEndTime === false) {
                                        $markAsOld = true;
                                    }
                                    if ($allowed_time > $session_now && $endSessionToTms > $session_now) {
                                        $markAsFuture = true;
                                    }
                                    $allowedEndTime = true;
                                    $allowed_time = 0;
                                }

                                if ($session_now >= $allowed_time && $allowedEndTime) {
                                    // Read only and accessible.
                                    $atLeastOneCourseIsVisible = true;
                                    if (api_get_setting('hide_courses_in_sessions') === 'false') {
                                        $courseUserHtml = CourseManager::get_logged_user_course_html(
                                            $course,
                                            $session_id,
                                            'session_course_item',
                                            true,
                                            $this->load_directories_preview
                                        );
                                        if (isset($courseUserHtml[1])) {
                                            $course_session = $courseUserHtml[1];
                                            $course_session['skill'] = isset($courseUserHtml['skill']) ? $courseUserHtml['skill'] : '';

                                            // Course option (show student progress)
                                            // This code will add new variables (Progress, Score, Certificate)
                                            if ($studentInfoProgress || $studentInfoScore || $studentInfoCertificate) {
                                                if ($studentInfoProgress) {
                                                    $progress = Tracking::get_avg_student_progress(
                                                        $user_id,
                                                        $course['course_code'],
                                                        [],
                                                        $session_id
                                                    );
                                                    $course_session['student_info']['progress'] = $progress === false ? null : $progress;
                                                }

                                                if ($studentInfoScore) {
                                                    $percentage_score = Tracking::get_avg_student_score(
                                                        $user_id,
                                                        $course['course_code'],
                                                        [],
                                                        $session_id
                                                    );
                                                    $course_session['student_info']['score'] = $percentage_score;
                                                }

                                                if ($studentInfoCertificate) {
                                                    $category = Category::load(
                                                        null,
                                                        null,
                                                        $course['course_code'],
                                                        null,
                                                        null,
                                                        $session_id
                                                    );
                                                    $course_session['student_info']['certificate'] = null;
                                                    if (isset($category[0])) {
                                                        if ($category[0]->is_certificate_available($user_id)) {
                                                            $course_session['student_info']['certificate'] = Display::label(
                                                                get_lang('Yes'),
                                                                'success'
                                                            );
                                                        } else {
                                                            $course_session['student_info']['certificate'] = Display::label(
                                                                get_lang('No')
                                                            );
                                                        }
                                                    }
                                                }
                                            }

                                            $course_session['extrafields'] = CourseManager::getExtraFieldsToBePresented($course['real_id']);

                                            if (api_get_configuration_value(
                                                    'enable_unsubscribe_button_on_my_course_page'
                                                )
                                                && '1' === $course['unsubscribe']
                                            ) {
                                                $course_session['unregister_button'] =
                                                    CoursesAndSessionsCatalog::return_unregister_button(
                                                        ['code' => $course['course_code']],
                                                        Security::get_existing_token(),
                                                        '',
                                                        ''
                                                    );
                                            }

                                            $html_courses_session[] = $course_session;
                                        }
                                    }
                                    $count_courses_session++;
                                }
                            }

                            // No courses to show.
                            if ($atLeastOneCourseIsVisible === false) {
                                if (empty($html_courses_session)) {
                                    continue;
                                }
                            }

                            if ($count_courses_session > 0) {
                                $params = [
                                    'id' => $session_id,
                                ];
                                $session_box = Display::getSessionTitleBox($session_id);
                                $coachId = $session_box['id_coach'];
                                $imageField = $extraFieldValue->get_values_by_handler_and_field_variable(
                                    $session_id,
                                    'image'
                                );

                                $params['category_id'] = $session_box['category_id'];
                                $params['title'] = $session_box['title'];
                                $params['id_coach'] = $coachId;
                                $params['coach_url'] = api_get_path(WEB_AJAX_PATH).
                                    'user_manager.ajax.php?a=get_user_popup&user_id='.$coachId;
                                $params['coach_name'] = !empty($session_box['coach']) ? $session_box['coach'] : null;
                                $params['coach_avatar'] = UserManager::getUserPicture(
                                    $coachId,
                                    USER_IMAGE_SIZE_SMALL
                                );
                                $params['date'] = $session_box['dates'];
                                $params['image'] = isset($imageField['value']) ? $imageField['value'] : null;
                                $params['duration'] = isset($session_box['duration']) ? ' '.$session_box['duration'] : null;
                                $params['show_actions'] = SessionManager::cantEditSession($session_id);

                                if ($collapsable) {
                                    $collapsableData = SessionManager::getCollapsableData(
                                        $user_id,
                                        $session_id,
                                        $extraFieldValue,
                                        $collapsableLink
                                    );
                                    $params['collapsed'] = $collapsableData['collapsed'];
                                    $params['collapsable_link'] = $collapsableData['collapsable_link'];
                                }

                                $params['show_description'] = $session_box['show_description'] == 1 && $portalShowDescription;
                                $params['description'] = $session_box['description'];
                                $params['visibility'] = $session_box['visibility'];
                                $params['show_simple_session_info'] = $showSimpleSessionInfo;
                                $params['course_list_session_style'] = $coursesListSessionStyle;
                                $params['num_users'] = $session_box['num_users'];
                                $params['num_courses'] = $session_box['num_courses'];
                                $params['course_categories'] = CourseManager::getCourseCategoriesFromCourseList(
                                    $html_courses_session
                                );
                                $params['courses'] = $html_courses_session;
                                $params['is_old'] = $markAsOld;
                                $params['is_future'] = $markAsFuture;

                                if ($showSimpleSessionInfo) {
                                    $params['subtitle'] = self::getSimpleSessionDetails(
                                        $session_box['coach'],
                                        $session_box['dates'],
                                        isset($session_box['duration']) ? $session_box['duration'] : null
                                    );
                                }

                                if ($gameModeIsActive) {
                                    $params['stars'] = GamificationUtils::getSessionStars(
                                        $params['id'],
                                        $this->user_id
                                    );
                                    $params['progress'] = GamificationUtils::getSessionProgress(
                                        $params['id'],
                                        $this->user_id
                                    );
                                    $params['points'] = GamificationUtils::getSessionPoints(
                                        $params['id'],
                                        $this->user_id
                                    );
                                }
                                $listSession[] = $params;
                                $sessionCount++;
                            }
                        }
                    } else {
                        // All sessions included in
                        $count_courses_session = 0;
                        $html_sessions = '';
                        if (isset($session_category['sessions'])) {
                            foreach ($session_category['sessions'] as $session) {
                                $session_id = $session['session_id'];

                                // Don't show empty sessions.
                                if (count($session['courses']) < 1) {
                                    continue;
                                }

                                $date_session_start = $session['access_start_date'];
                                $date_session_end = $session['access_end_date'];
                                $coachAccessStartDate = $session['coach_access_start_date'];
                                $coachAccessEndDate = $session['coach_access_end_date'];
                                $html_courses_session = [];
                                $count = 0;
                                $markAsOld = false;
                                $markAsFuture = false;

                                foreach ($session['courses'] as $course) {
                                    $is_coach_course = api_is_coach($session_id, $course['real_id']);
                                    $allowed_time = 0;
                                    $allowedEndTime = true;

                                    if (!empty($date_session_start)) {
                                        if ($is_coach_course) {
                                            $allowed_time = api_strtotime($coachAccessStartDate);
                                        } else {
                                            $allowed_time = api_strtotime($date_session_start);
                                        }

                                        if (!isset($_GET['history'])) {
                                            if (!empty($date_session_end)) {
                                                if ($is_coach_course) {
                                                    // if coach end date is empty we use the default end date
                                                    if (empty($coachAccessEndDate)) {
                                                        $endSessionToTms = api_strtotime($date_session_end);
                                                        if ($session_now > $endSessionToTms) {
                                                            $allowedEndTime = false;
                                                        }
                                                    } else {
                                                        $endSessionToTms = api_strtotime($coachAccessEndDate);
                                                        if ($session_now > $endSessionToTms) {
                                                            $allowedEndTime = false;
                                                        }
                                                    }
                                                } else {
                                                    $endSessionToTms = api_strtotime($date_session_end);
                                                    if ($session_now > $endSessionToTms) {
                                                        $allowedEndTime = false;
                                                    }
                                                }
                                            }
                                        }
                                    }

                                    if ($showAllSessions) {
                                        if ($allowed_time < $session_now && $allowedEndTime == false) {
                                            $markAsOld = true;
                                        }
                                        if ($allowed_time > $session_now && $endSessionToTms > $session_now) {
                                            $markAsFuture = true;
                                        }
                                        $allowedEndTime = true;
                                        $allowed_time = 0;
                                    }

                                    if ($session_now >= $allowed_time && $allowedEndTime) {
                                        if (api_get_setting('hide_courses_in_sessions') === 'false') {
                                            $c = CourseManager::get_logged_user_course_html(
                                                $course,
                                                $session_id,
                                                'session_course_item'
                                            );
                                            if (isset($c[1])) {
                                                $html_courses_session[] = $c[1];
                                            }
                                        }
                                        $count_courses_session++;
                                        $count++;
                                    }
                                }

                                $sessionParams = [];
                                // Category
                                if ($count > 0) {
                                    $session_box = Display::getSessionTitleBox($session_id);
                                    $sessionParams[0]['id'] = $session_id;
                                    $sessionParams[0]['date'] = $session_box['dates'];
                                    $sessionParams[0]['duration'] = isset($session_box['duration']) ? ' '.$session_box['duration'] : null;
                                    $sessionParams[0]['course_list_session_style'] = $coursesListSessionStyle;
                                    $sessionParams[0]['title'] = $session_box['title'];
                                    $sessionParams[0]['subtitle'] = (!empty($session_box['coach']) ? $session_box['coach'].' | ' : '').$session_box['dates'];
                                    $sessionParams[0]['show_actions'] = SessionManager::cantEditSession($session_id);
                                    $sessionParams[0]['courses'] = $html_courses_session;
                                    $sessionParams[0]['show_simple_session_info'] = $showSimpleSessionInfo;
                                    $sessionParams[0]['coach_name'] = !empty($session_box['coach']) ? $session_box['coach'] : null;
                                    $sessionParams[0]['is_old'] = $markAsOld;
                                    $sessionParams[0]['is_future'] = $markAsFuture;

                                    if ($collapsable) {
                                        $collapsableData = SessionManager::getCollapsableData(
                                            $user_id,
                                            $session_id,
                                            $extraFieldValue,
                                            $collapsableLink
                                        );
                                        $sessionParams[0]['collapsable_link'] = $collapsableData['collapsable_link'];
                                        $sessionParams[0]['collapsed'] = $collapsableData['collapsed'];
                                    }

                                    if ($showSimpleSessionInfo) {
                                        $sessionParams[0]['subtitle'] = self::getSimpleSessionDetails(
                                            $session_box['coach'],
                                            $session_box['dates'],
                                            isset($session_box['duration']) ? $session_box['duration'] : null
                                        );
                                    }
                                    $this->tpl->assign('session', $sessionParams);
//                                    $this->tpl->assign('show_tutor', api_get_setting('show_session_coach') === 'true');
//                                    $this->tpl->assign('gamification_mode', $gameModeIsActive);
                                    $this->tpl->assign(
                                        'remove_session_url',
                                        api_get_configuration_value('remove_session_url')
                                    );
                                    $this->tpl->assign(
                                        'hide_session_dates_in_user_portal',
                                        api_get_configuration_value('hide_session_dates_in_user_portal')
                                    );

                                    if ($viewGridCourses) {
                                        $html_sessions .= $this->tpl->fetch(
                                            $this->tpl->get_template('/user_portal/grid_session.tpl')
                                        );
                                    } else {
                                        $html_sessions .= $this->tpl->fetch(
                                            $this->tpl->get_template('user_portal/classic_session.tpl')
                                        );
                                    }
                                    $sessionCount++;
                                }
                            }
                        }

                        if ($count_courses_session > 0) {
                            $categoryParams = [
                                'id' => $session_category['session_category']['id'],
                                'title' => $session_category['session_category']['name'],
                                'show_actions' => api_is_platform_admin(),
                                'subtitle' => '',
                                'sessions' => $html_sessions,
                            ];

                            $session_category_start_date = $session_category['session_category']['date_start'];
                            $session_category_end_date = $session_category['session_category']['date_end'];
                            if ($session_category_start_date == '0000-00-00') {
                                $session_category_start_date = '';
                            }

                            if ($session_category_end_date == '0000-00-00') {
                                $session_category_end_date = '';
                            }

                            if (!empty($session_category_start_date) &&
                                !empty($session_category_end_date)
                            ) {
                                $categoryParams['subtitle'] = sprintf(
                                    get_lang('FromDateXToDateY'),
                                    $session_category_start_date,
                                    $session_category_end_date
                                );
                            } else {
                                if (!empty($session_category_start_date)) {
                                    $categoryParams['subtitle'] = get_lang('From').' '.$session_category_start_date;
                                }

                                if (!empty($session_category_end_date)) {
                                    $categoryParams['subtitle'] = get_lang('Until').' '.$session_category_end_date;
                                }
                            }

                            $this->tpl->assign('session_category', $categoryParams);
                            $sessions_with_category .= $this->tpl->fetch(
                                $this->tpl->get_template('user_portal/session_category.tpl')
                            );
                        }
                    }
                }

                $allCoursesInSessions = [];
                foreach ($listSession as $currentSession) {
                    $coursesInSessions = $currentSession['courses'];
                    unset($currentSession['courses']);
                    foreach ($coursesInSessions as $coursesInSession) {
                        $coursesInSession['session'] = $currentSession;
                        $allCoursesInSessions[] = $coursesInSession;
                    }
                }

                $this->tpl->assign('all_courses', $allCoursesInSessions);
                $this->tpl->assign('session', $listSession);
//                $this->tpl->assign('show_tutor', (api_get_setting('show_session_coach') === 'true' ? true : false));
//                $this->tpl->assign('gamification_mode', $gameModeIsActive);
                $this->tpl->assign('remove_session_url', api_get_configuration_value('remove_session_url'));
                $this->tpl->assign(
                    'hide_session_dates_in_user_portal',
                    api_get_configuration_value('hide_session_dates_in_user_portal')
                );

                if ($viewGridCourses) {
                    $sessions_with_no_category = $this->tpl->fetch(
                        $this->tpl->get_template('/user_portal/grid_session.tpl')
                    );
                } else {
                    $sessions_with_no_category = $this->tpl->fetch(
                        $this->tpl->get_template('user_portal/classic_session.tpl')
                    );
                }
            }
        }
        $data = [
            'courses' => $courseCompleteList,
            'sessions' => $session_categories,
            'html' => trim($specialCourseList.$sessions_with_category.$sessions_with_no_category.$listCourse),
            'session_count' => $sessionCount,
            'course_count' => $courseCount,
        ];
print('<pre>'.print_r($data, true).'</pre>'); die;
        return [
            'courses' => $courseCompleteList,
            'sessions' => $session_categories,
            'html' => trim($specialCourseList.$sessions_with_category.$sessions_with_no_category.$listCourse),
            'session_count' => $sessionCount,
            'course_count' => $courseCount,
        ];
    }


    /**
     * @param $listA
     * @param $listB
     *
     * @return int
     */
    private static function compareByCourse($listA, $listB)
    {
        if ($listA['userCatTitle'] == $listB['userCatTitle']) {
            if ($listA['title'] == $listB['title']) {
                return 0;
            }

            if ($listA['title'] > $listB['title']) {
                return 1;
            }

            return -1;
        }

        if ($listA['userCatTitle'] > $listB['userCatTitle']) {
            return 1;
        }

        return -1;
    }

    /**
     * Generate the HTML code for items when displaying the right-side blocks.
     *
     * @return string
     */
    private static function returnRightBlockItems(array $items)
    {
        $my_account_content = '';
        foreach ($items as $item) {
            if (empty($item['link']) && empty($item['title'])) {
                continue;
            }

            $my_account_content .= '<li class="list-group-item '.(empty($item['class']) ? '' : $item['class']).'">'
                .(empty($item['icon']) ? '' : '<span class="item-icon">'.$item['icon'].'</span>')
                .'<a href="'.$item['link'].'">'.$item['title'].'</a>'
                .'</li>';
        }

        return '<ul class="list-group">'.$my_account_content.'</ul>';
    }

    /**
     * Return HTML code for personal user course category.
     *
     * @param $id
     * @param $title
     *
     * @return string
     */
    private static function getHtmlForUserCategory($id, $title)
    {
        if ($id == 0) {
            return '';
        }
        $icon = Display::return_icon(
            'folder_yellow.png',
            $title,
            ['class' => 'sessionView'],
            ICON_SIZE_LARGE
        );

        return "<div class='session-view-user-category'>$icon<span>$title</span></div>";
    }

    /**
     * return HTML code for course display in session view.
     *
     * @param array $courseInfo
     * @param       $userCategoryId
     * @param bool  $displayButton
     * @param       $loadDirs
     *
     * @return string
     */
    private static function getHtmlForCourse(
        $courseInfo,
        $userCategoryId,
        $displayButton = false,
        $loadDirs
    ) {
        if (empty($courseInfo)) {
            return '';
        }

        $id = $courseInfo['real_id'];
        $title = $courseInfo['title'];
        $code = $courseInfo['code'];

        $class = 'session-view-lvl-6';
        if ($userCategoryId != 0 && !$displayButton) {
            $class = 'session-view-lvl-7';
        }

        $class2 = 'session-view-lvl-6';
        if ($displayButton || $userCategoryId != 0) {
            $class2 = 'session-view-lvl-7';
        }

        $button = '';
        if ($displayButton) {
            $button = '<input id="session-view-button-'.intval(
                    $id
                ).'" class="btn btn-default btn-sm" type="button" onclick="hideUnhide(\'courseblock-'.intval(
                    $id
                ).'\', \'session-view-button-'.intval($id).'\', \'+\', \'-\')" value="+" />';
        }

        $icon = Display::return_icon(
            'blackboard.png',
            $title,
            ['class' => 'sessionView'],
            ICON_SIZE_LARGE
        );

        $courseLink = $courseInfo['course_public_url'].'?id_session=0';

        // get html course params
        $courseParams = CourseManager::getCourseParamsForDisplay($id, $loadDirs);
        $teachers = '';
        $rightActions = '';

        // teacher list
        if (!empty($courseParams['teachers'])) {
            $teachers = '<p class="'.$class2.' view-by-session-teachers">'.$courseParams['teachers'].'</p>';
        }

        // notification
        if (!empty($courseParams['right_actions'])) {
            $rightActions = '<div class="pull-right">'.$courseParams['right_actions'].'</div>';
        }

        $notifications = isset($courseParams['notifications']) ? $courseParams['notifications'] : '';

        return "<div>
                    $button
                    <span class='$class'>$icon
                        <a class='sessionView' href='$courseLink'>$title</a>
                    </span>
                    $notifications
                    $rightActions
                </div>
                $teachers";
    }

    /**
     * return HTML code for session category.
     *
     * @param $id
     * @param $title
     *
     * @return string
     */
    private static function getHtmlSessionCategory($id, $title)
    {
        if ($id == 0) {
            return '';
        }

        $icon = Display::return_icon(
            'folder_blue.png',
            $title,
            ['class' => 'sessionView'],
            ICON_SIZE_LARGE
        );

        return "<div class='session-view-session-category'>
                <span class='session-view-lvl-2'>
                    $icon
                    <span>$title</span>
                </span>
                </div>";
    }

    /**
     * return HTML code for session.
     *
     * @param int    $id                session id
     * @param string $title             session title
     * @param int    $categorySessionId
     * @param array  $courseInfo
     *
     * @return string
     */
    private static function getHtmlForSession($id, $title, $categorySessionId, $courseInfo)
    {
        $html = '';
        if ($categorySessionId == 0) {
            $class1 = 'session-view-lvl-2'; // session
            $class2 = 'session-view-lvl-4'; // got to course in session link
        } else {
            $class1 = 'session-view-lvl-3'; // session
            $class2 = 'session-view-lvl-5'; // got to course in session link
        }

        $icon = Display::return_icon(
            'session.png',
            $title,
            ['class' => 'sessionView'],
            ICON_SIZE_LARGE
        );
        $courseLink = $courseInfo['course_public_url'].'?id_session='.intval($id);

        $html .= "<span class='$class1 session-view-session'>$icon$title</span>";
        $html .= '<div class="'.$class2.' session-view-session-go-to-course-in-session">
                  <a class="" href="'.$courseLink.'">'.get_lang('GoToCourseInsideSession').'</a></div>';

        return '<div>'.$html.'</div>';
    }

    /**
     * Filter the course list by category code.
     *
     * @param array  $courseList   course list
     * @param string $categoryCode
     *
     * @return array
     */
    private static function filterByCategory($courseList, $categoryCode)
    {
        return array_filter(
            $courseList,
            function ($courseInfo) use ($categoryCode) {
                if (isset($courseInfo['category_code']) &&
                    $courseInfo['category_code'] === $categoryCode
                ) {
                    return true;
                }

                return false;
            }
        );
    }

    /**
     * Get the session coach name, duration or dates
     * when $_configuration['show_simple_session_info'] is enabled.
     *
     * @param string      $coachName
     * @param string      $dates
     * @param string|null $duration  Optional
     *
     * @return string
     */
    private static function getSimpleSessionDetails($coachName, $dates, $duration = null)
    {
        $strDetails = [];
        if (!empty($coachName)) {
            $strDetails[] = $coachName;
        }

        $strDetails[] = !empty($duration) ? $duration : $dates;

        return implode(' | ', $strDetails);
    }
}
