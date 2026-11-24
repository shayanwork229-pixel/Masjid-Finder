<?php

use App\Http\Controllers\AdminPageController;
use App\Http\Controllers\EnhancedContentController;
use App\Http\Controllers\PublicPageController;
use App\Http\Controllers\QiblaController;
use App\Http\Controllers\Ramadan\RamadanController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\Events\EventController;
use App\Http\Controllers\LiveBayanController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\IslamicArticleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;

Route::get('/', [PublicPageController::class, 'home'])->name('home');
Route::get('/about', [PublicPageController::class, 'about'])->name('about');
Route::get('/about/details', [PublicPageController::class, 'aboutDetails'])->name('about.details');
Route::get('/contact', [PublicPageController::class, 'contact'])->name('contact');
Route::post('/contact', [PublicPageController::class, 'storeContact'])->name('contact.store');
Route::get('/masjids', [PublicPageController::class, 'masjids'])->name('masjids.index');
Route::get('/masjids/{masjid}', [PublicPageController::class, 'showMasjid'])->name('masjids.show');
Route::get('/map', [PublicPageController::class, 'map'])->name('map');
Route::get('/timings', [PublicPageController::class, 'timings'])->name('timings.index');
Route::get('/timings/details', [PublicPageController::class, 'timingsDetails'])->name('timings.details');
Route::get('/timings/juma-eid', [PublicPageController::class, 'jumaEid'])->name('timings.juma');
Route::get('/timings/juma-eid/{masjid}', [PublicPageController::class, 'jumaEidDetails'])->name('timings.juma.details');
Route::get('/pdf-timetable', [PublicPageController::class, 'pdfTimetable'])->name('pdf-timetable');
Route::get('/qibla', [QiblaController::class, 'index'])->name('qibla');
Route::get('/ramadan', [RamadanController::class, 'index'])->name('ramadan');
Route::get('/hijri-calendar', [EnhancedContentController::class, 'hijriCalendar'])->name('hijri.calendar');
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
Route::get('/announcements', [EnhancedContentController::class, 'announcements'])->name('announcements.index');

Route::get('/quran-classes', [EnhancedContentController::class, 'quranClasses'])->name('quran-classes.index');
// ── Live Bayan Library ──────────────────────────────────────────
Route::get('/live-bayan', [LiveBayanController::class, 'index'])->name('live-bayan.index');
Route::get('/live-bayan/{bayan}', [LiveBayanController::class, 'show'])->name('live-bayan.show');

// ── Islamic Gallery ──────────────────────────────────────────────
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');
Route::get('/gallery/{image}', [GalleryController::class, 'show'])->name('gallery.show');
Route::post('/gallery/{image}/like', [GalleryController::class, 'like'])->name('gallery.like');

// ── Islamic Articles ─────────────────────────────────────────────
Route::get('/articles', [IslamicArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{article:slug}', [IslamicArticleController::class, 'show'])->name('articles.show');

Route::get('/search', [SearchController::class, 'index'])->name('search');

Route::get('/admin', [AdminPageController::class, 'dashboard'])->name('admin.dashboard');
Route::get('/admin/masjids', [AdminPageController::class, 'masjids'])->name('admin.masjids');
Route::get('/admin/add-masjid', [AdminPageController::class, 'addMasjid'])->name('admin.add-masjid');
Route::post('/admin/add-masjid', [AdminPageController::class, 'storeMasjid'])->name('admin.store-masjid');
Route::get('/admin/edit-masjid/{masjid}', [AdminPageController::class, 'editMasjid'])->name('admin.edit-masjid');
Route::put('/admin/edit-masjid/{masjid}', [AdminPageController::class, 'updateMasjid'])->name('admin.update-masjid');
Route::post('/admin/delete-masjid', [AdminPageController::class, 'deleteMasjid'])->name('admin.delete-masjid');
Route::get('/admin/timings', [AdminPageController::class, 'timings'])->name('admin.timings');
Route::post('/admin/timings', [AdminPageController::class, 'updateTimings'])->name('admin.update-timings');
Route::get('/admin/settings', [AdminPageController::class, 'settings'])->name('admin.settings');
Route::post('/admin/settings', [AdminPageController::class, 'updateSettings'])->name('admin.update-settings');
Route::get('/admin/ramadan', [RamadanController::class, 'adminRamadan'])->name('admin.ramadan');
Route::post('/admin/ramadan', [RamadanController::class, 'storeRamadan'])->name('admin.store-ramadan');
Route::get('/admin/events', [EventController::class, 'adminEvents'])->name('admin.events');
Route::get('/admin/events/create', [EventController::class, 'createEvent'])->name('admin.create-event');
Route::post('/admin/events', [EventController::class, 'storeEvent'])->name('admin.store-event');
Route::get('/admin/events/{event}/edit', [EventController::class, 'editEvent'])->name('admin.edit-event');
Route::put('/admin/events/{event}', [EventController::class, 'updateEvent'])->name('admin.update-event');
Route::post('/admin/events/delete', [EventController::class, 'deleteEvent'])->name('admin.delete-event');
Route::get('/admin/masjids/{masjid}/gallery', [EnhancedContentController::class, 'adminGallery'])->name('admin.gallery');
Route::post('/admin/masjids/{masjid}/gallery', [EnhancedContentController::class, 'uploadGallery'])->name('admin.upload-gallery');
Route::delete('/admin/gallery/{image}', [EnhancedContentController::class, 'deleteGalleryImage'])->name('admin.delete-gallery-image');
Route::post('/admin/gallery/reorder', [EnhancedContentController::class, 'reorderGallery'])->name('admin.reorder-gallery');
Route::get('/admin/facilities', [EnhancedContentController::class, 'adminFacilities'])->name('admin.facilities');
Route::post('/admin/facilities', [EnhancedContentController::class, 'storeFacility'])->name('admin.store-facility');
Route::post('/admin/facilities/attach', [EnhancedContentController::class, 'attachFacility'])->name('admin.attach-facility');
Route::get('/admin/announcements', [EnhancedContentController::class, 'adminAnnouncements'])->name('admin.announcements');
Route::post('/admin/announcements', [EnhancedContentController::class, 'storeAnnouncement'])->name('admin.store-announcement');

Route::get('/donations', [DonationController::class, 'index'])->name('donations.index');
Route::get('/donations/{campaign}', [DonationController::class, 'show'])->name('donations.show');
Route::post('/donations/{campaign}/donate', [DonationController::class, 'donate'])->name('donations.donate');

Route::get('/admin/donations', [DonationController::class, 'adminIndex'])->name('admin.donations');
Route::get('/admin/donations/create', [DonationController::class, 'create'])->name('admin.donations.create');
Route::post('/admin/donations', [DonationController::class, 'store'])->name('admin.donations.store');
Route::get('/admin/donations/{campaign}/edit', [DonationController::class, 'edit'])->name('admin.donations.edit');
Route::put('/admin/donations/{campaign}', [DonationController::class, 'update'])->name('admin.donations.update');
Route::delete('/admin/donations/{campaign}', [DonationController::class, 'destroy'])->name('admin.donations.destroy');
Route::post('/admin/donations/{campaign}/status', [DonationController::class, 'status'])->name('admin.donations.status');
Route::get('/admin/donations/{campaign}/history', [DonationController::class, 'history'])->name('admin.donations.history');

// ── Admin: Live Bayan Library ───────────────────────────────────
Route::get('/admin/bayans', [LiveBayanController::class, 'adminIndex'])->name('admin.bayans.index');
Route::get('/admin/bayans/create', [LiveBayanController::class, 'adminCreate'])->name('admin.bayans.create');
Route::post('/admin/bayans', [LiveBayanController::class, 'adminStore'])->name('admin.bayans.store');
Route::get('/admin/bayans/{bayan}/edit', [LiveBayanController::class, 'adminEdit'])->name('admin.bayans.edit');
Route::put('/admin/bayans/{bayan}', [LiveBayanController::class, 'adminUpdate'])->name('admin.bayans.update');
Route::post('/admin/bayans/delete', [LiveBayanController::class, 'adminDestroy'])->name('admin.bayans.destroy');

// ── Admin: Islamic Gallery ──────────────────────────────────────
Route::get('/admin/gallery-manage', [GalleryController::class, 'adminIndex'])->name('admin.gallery.index');
Route::get('/admin/gallery-manage/create', [GalleryController::class, 'adminCreate'])->name('admin.gallery.create');
Route::post('/admin/gallery-manage', [GalleryController::class, 'adminStore'])->name('admin.gallery.store');
Route::get('/admin/gallery-manage/{image}/edit', [GalleryController::class, 'adminEdit'])->name('admin.gallery.edit');
Route::put('/admin/gallery-manage/{image}', [GalleryController::class, 'adminUpdate'])->name('admin.gallery.update');
Route::post('/admin/gallery-manage/delete', [GalleryController::class, 'adminDestroy'])->name('admin.gallery.destroy');

// ── Admin: Islamic Articles ─────────────────────────────────────
Route::get('/admin/articles', [IslamicArticleController::class, 'adminIndex'])->name('admin.articles.index');
Route::get('/admin/articles/create', [IslamicArticleController::class, 'adminCreate'])->name('admin.articles.create');
Route::post('/admin/articles', [IslamicArticleController::class, 'adminStore'])->name('admin.articles.store');
Route::get('/admin/articles/{article}/edit', [IslamicArticleController::class, 'adminEdit'])->name('admin.articles.edit');
Route::put('/admin/articles/{article}', [IslamicArticleController::class, 'adminUpdate'])->name('admin.articles.update');
Route::post('/admin/articles/delete', [IslamicArticleController::class, 'adminDestroy'])->name('admin.articles.destroy');

