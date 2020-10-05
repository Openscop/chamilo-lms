<?php


class UserController extends IndexManager
{
    public function getCourseList(){
        return CourseHelper::getNewCourse();
    }

    public function setOneColumnTemplate(){
        $tpl = $this->tpl->get_template('layout/blank.tpl');
        $this->tpl->display($tpl);
    }

}
