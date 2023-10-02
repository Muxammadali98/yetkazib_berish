<?php

namespace App\Interfaces\Repositories;

interface TelegramRepositoryInterface
{
    public function getStartMessage();
    public function startKeyboard();
    public function closeKeyboard();
    public function getActiveQuestionType();
    public function getQuestionsOrderByStep();
    public function getTempQuestion($client_id);
    public function getButtonText($id);
    public function checkKeyboard();
    public function checkApplicationData($client_id);
    public function getInactiveApplication($client_id);
    public function getQuestionIdInTemp($client_id);
    public function getPhoto($photos);
    public function phoneKeyboard();
    public function getCategoryKeyboardWithPagination($page);
    public function getSubCategoryKeyboardWithPagination($category_id, $page);
    public function hasSubCategory($category_id);
    public function getCategoryInfo($category_id);
    public function getPDF($category_id);
    public function isActiveClient($client_id);
    public function getRegions();
    public function getBranchesKeyboard();
    public function homeKeyboard();
    public function getBranchInfo($region_id);
    public function channelKeyboard();
}
