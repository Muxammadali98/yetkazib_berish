<?php

namespace App\Providers;

use App\Interfaces\Api\Repositories\PhoneVerificationRepositoryInterface;
use App\Interfaces\Api\Repositories\TicketRepositoryInterface;
use App\Interfaces\Repositories\DashboardRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class BindServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            \App\Interfaces\Repositories\StartRepositoryInterface::class,
            \App\Repositories\StartRepository::class
        );
        $this->app->bind(
            \App\Interfaces\Services\StartServiceInterface::class,
            \App\Services\StartService::class
        );
        $this->app->bind(
            \App\Interfaces\Repositories\QuestionRepositoryInterface::class,
            \App\Repositories\QuestionRepository::class
        );
        $this->app->bind(
            \App\Interfaces\Services\QuestionServiceInterface::class,
            \App\Services\QuestionService::class
        );
        $this->app->bind(
            \App\Interfaces\Repositories\CategoryRepositoryInterface::class,
            \App\Repositories\CategoryRepository::class
        );
        $this->app->bind(
            \App\Interfaces\Services\CategoryServiceInterface::class,
            \App\Services\CategoryService::class
        );
        $this->app->bind(
            \App\Interfaces\Repositories\UserRepositoryInterface::class,
            \App\Repositories\UserRepository::class
        );
        $this->app->bind(
            \App\Interfaces\Services\UserServiceInterface::class,
            \App\Services\UserService::class
        );
        $this->app->bind(
            \App\Interfaces\Repositories\TelegramRepositoryInterface::class,
            \App\Repositories\TelegramRepository::class
        );
        $this->app->bind(
            \App\Interfaces\Services\TelegramServiceInterface::class,
            \App\Services\TelegramService::class
        );
        $this->app->bind(
            \App\Interfaces\Repositories\RegionRepositoryInterface::class,
            \App\Repositories\RegionRepository::class
        );
        $this->app->bind(
            \App\Interfaces\Services\RegionServiceInterface::class,
            \App\Services\RegionService::class
        );
        $this->app->bind(
            \App\Interfaces\Repositories\MessageRepositoryInterface::class,
            \App\Repositories\MessageRepository::class
        );
        $this->app->bind(
            \App\Interfaces\Services\MessageServiceInterface::class,
            \App\Services\MessageService::class
        );
        $this->app->bind(
            \App\Interfaces\Repositories\AnswerRepositoryInterface::class,
            \App\Repositories\AnswerRepository::class
        );
        $this->app->bind(
            \App\Interfaces\Services\AnswerServiceInterface::class,
            \App\Services\AnswerService::class
        );
        $this->app->bind(
            \App\Interfaces\Services\LogServiceInterface::class,
            \App\Services\LogService::class
        );
        $this->app->bind(
            \App\Interfaces\Repositories\LogRepositoryInterface::class,
            \App\Repositories\LogRepository::class
        );
        $this->app->bind(
            \App\Interfaces\Repositories\DashboardRepositoryInterface::class,
            \App\Repositories\DashboardRepository::class
        );
        $this->app->bind(
            \App\Interfaces\Repositories\TicketRepositoryInterface::class,
            \App\Repositories\TicketRepository::class
        );
        $this->app->bind(
            \App\Interfaces\Services\TicketServiceInterface::class,
            \App\Services\TicketService::class
        );
        $this->app->bind(
            \App\Interfaces\Repositories\SupplierAssignmentRepositoryInterface::class,
            \App\Repositories\SupplierAssignmentRepository::class
        );
        $this->app->bind(
            \App\Interfaces\Services\SupplierAssignmentServiceInterface::class,
            \App\Services\SupplierAssignmentService::class
        );

        $this->app->bind(
            TicketRepositoryInterface::class,
            \App\Repositories\Api\TicketRepository::class
        );
        $this->app->bind(
            \App\Interfaces\Api\Services\TicketServiceInterface::class,
            \App\Services\Api\TicketService::class
        );

        $this->app->bind(
            \App\Interfaces\Api\Repositories\SupplierAssignmentRepositoryInterface::class,
            \App\Repositories\Api\SupplierAssignmentRepository::class
        );
        $this->app->bind(
            \App\Interfaces\Api\Services\SupplierAssignmentServiceInterface::class,
            \App\Services\Api\SupplierAssignmentService::class
        );

        $this->app->bind(
            \App\Interfaces\Api\Repositories\ProfileRepositoryInterface::class,
            \App\Repositories\Api\ProfileRepository::class
        );

        $this->app->bind(
            \App\Interfaces\Api\Services\ProfileServiceInterface::class,
            \App\Services\Api\ProfileService::class
        );
        $this->app->bind(
            \App\Interfaces\Api\Services\PhoneVerificationServiceInterface::class,
            \App\Services\Api\PhoneVerificationService::class
        );
        $this->app->bind(
            \App\Interfaces\Api\Repositories\PhoneVerificationRepositoryInterface::class,
            \App\Repositories\Api\PhoneVerificationRepository::class
        );
        $this->app->bind(
            \App\Interfaces\Repositories\AdditionalNoticeRepositoryInterface::class,
            \App\Repositories\AdditionalNoticeRepository::class
        );
        $this->app->bind(
            \App\Interfaces\Services\AdditionalNoticeServiceInterface::class,
            \App\Services\AdditionalNoticeService::class
        );
        $this->app->bind(
            \App\Interfaces\Api\Repositories\NotificationRepositoryInterface::class,
            \App\Repositories\Api\NotificationRepository::class
        );
        $this->app->bind(
            \App\Interfaces\Api\Services\NotificationServiceInterface::class,
            \App\Services\Api\NotificationService::class
        );
        $this->app->bind(
            \App\Interfaces\Services\ApplicationCommentServiceInterface::class,
            \App\Services\ApplicationCommentService::class
        );
        $this->app->bind(
            \App\Interfaces\Repositories\ApplicationCommentRepositoryInterface::class,
            \App\Repositories\ApplicationCommentRepository::class
        );
        $this->app->bind(
            \App\Interfaces\Repositories\CarRepositoryInterface::class,
            \App\Repositories\CarRepository::class
        );
        $this->app->bind(
            \App\Interfaces\Services\CarServiceInterface::class,
            \App\Services\CarService::class
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
