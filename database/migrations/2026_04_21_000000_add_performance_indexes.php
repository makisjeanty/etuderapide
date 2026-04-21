<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Performance optimization indexes for frequently queried columns.
     */
    public function up(): void
    {
        // Posts table indexes
        Schema::table('posts', function (Blueprint $table) {
            $table->index('is_published', 'posts_is_published_idx');
            $table->index('published_at', 'posts_published_at_idx');
            $table->index('category_id', 'posts_category_id_idx');
            $table->index(['is_published', 'published_at'], 'posts_published_filter_idx');
            $table->index(['user_id', 'created_at'], 'posts_author_latest_idx');
        });

        // Projects table indexes
        Schema::table('projects', function (Blueprint $table) {
            $table->index('status', 'projects_status_idx');
            $table->index('is_featured', 'projects_is_featured_idx');
            $table->index('category_id', 'projects_category_id_idx');
            $table->index(['status', 'is_featured', 'updated_at'], 'projects_featured_published_idx');
            $table->index(['user_id', 'created_at'], 'projects_author_latest_idx');
        });

        // Services table indexes
        Schema::table('services', function (Blueprint $table) {
            $table->index('is_active', 'services_is_active_idx');
            $table->index('category_id', 'services_category_id_idx');
            $table->index(['is_active', 'name'], 'services_active_sorted_idx');
            $table->index(['user_id', 'created_at'], 'services_author_latest_idx');
        });

        // Leads table indexes
        Schema::table('leads', function (Blueprint $table) {
            $table->index('status', 'leads_status_idx');
            $table->index('created_at', 'leads_created_at_idx');
            $table->index('service_interest', 'leads_service_interest_idx');
            $table->index(['status', 'created_at'], 'leads_status_date_idx');
        });

        // Categories table indexes
        Schema::table('categories', function (Blueprint $table) {
            $table->index('type', 'categories_type_idx');
            $table->index(['type', 'slug'], 'categories_type_slug_idx');
        });

        // Users table indexes (if not already present)
        Schema::table('users', function (Blueprint $table) {
            if (! $this->hasIndex($table, 'users', 'users_email_idx')) {
                $table->index('email', 'users_email_idx');
            }
            if (! $this->hasIndex($table, 'users', 'users_is_admin_idx')) {
                $table->index('is_admin', 'users_is_admin_idx');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex('posts_is_published_idx');
            $table->dropIndex('posts_published_at_idx');
            $table->dropIndex('posts_category_id_idx');
            $table->dropIndex('posts_published_filter_idx');
            $table->dropIndex('posts_author_latest_idx');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropIndex('projects_status_idx');
            $table->dropIndex('projects_is_featured_idx');
            $table->dropIndex('projects_category_id_idx');
            $table->dropIndex('projects_featured_published_idx');
            $table->dropIndex('projects_author_latest_idx');
        });

        Schema::table('services', function (Blueprint $table) {
            $table->dropIndex('services_is_active_idx');
            $table->dropIndex('services_category_id_idx');
            $table->dropIndex('services_active_sorted_idx');
            $table->dropIndex('services_author_latest_idx');
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->dropIndex('leads_status_idx');
            $table->dropIndex('leads_created_at_idx');
            $table->dropIndex('leads_service_interest_idx');
            $table->dropIndex('leads_status_date_idx');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex('categories_type_idx');
            $table->dropIndex('categories_type_slug_idx');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_email_idx');
            $table->dropIndex('users_is_admin_idx');
        });
    }

    /**
     * Check if index exists on table.
     */
    private function hasIndex(Blueprint $table, string $tableName, string $indexName): bool
    {
        $schemaManager = Schema::getConnection()->getDoctrineSchemaManager();
        $indexes = $schemaManager->listTableIndexes($tableName);
        
        return isset($indexes[$indexName]);
    }
};
