<?php
// ۱- گرفتن داده‌ها
$url = "https://www.momtaznews.com/%D8%AC%D8%AF%DB%8C%D8%AF%D8%AA%D8%B1%DB%8C%D9%86-%D8%AC%D8%AF%D9%88%D9%84-%D9%81%D8%B1%D9%88%D8%B4-%D9%81%DB%8C%D9%84%D9%85%D9%87%D8%A7%DB%8C-%D8%AF%D8%B1-%D8%AD%D8%A7%D9%84-%D8%A7%DA%A9%D8%B1%D8%A7/";

$html = file_get_contents($url);

libxml_use_internal_errors(true);
$dom = new DOMDocument();
$dom->loadHTML($html);
libxml_clear_errors();

$xpath = new DOMXPath($dom);
$items = $xpath->query('//div[contains(@class, "box-info")]');

$result = [];

foreach ($items as $item) {
    $movieNode = $xpath->query('.//div[contains(@class, "center-film")]', $item);
    $movie = $movieNode->length > 0 ? trim($movieNode[0]->textContent) : '';

    $reportInfos = $xpath->query('.//div[contains(@class, "report-info")]', $item);
    $startDate = $sales = null;
    foreach ($reportInfos as $info) {
        $text = trim($info->textContent);
        if (strpos($text, 'شروع اکران') !== false) {
            $startDate = trim(str_replace('شروع اکران:', '', $text));
        } elseif (strpos($text, 'فروش') !== false) {
            $sales = trim(str_replace('فروش:', '', $text));
        }
    }

    $imgNode = $xpath->query('.//preceding-sibling::div[contains(@class,"box-img-div")]//img', $item);
    $imgSrc = $imgNode->length > 0 ? $imgNode[0]->getAttribute('src') : '';

    $result[] = [
        'movie' => $movie,
        'start_date' => $startDate,
        'sales' => $sales,
        'img' => $imgSrc
    ];
}

function enToFaNumber($string)
{
    $en = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    $fa = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
    return str_replace($en, $fa, $string);
}

?>




<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta name="description" content="سینما تیکت - خرید آنلاین بلیط سینما">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سینما تیکت</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;700&display=swap&subset=arabic"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* انیمیشن پالس آهسته */
        @keyframes bounce-slow {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-6px);
            }
        }

        .animate-bounce-slow {
            animation: bounce-slow 2s infinite;
        }

        #backToTop {
            transition: all 0.3s ease;
        }

        * {
            font-family: 'Vazirmatn', sans-serif;
        }

        .swiper {
            width: 100%;
            height: 500px;
            border-radius: 15px;
            overflow: hidden;
        }

        .swiper-slide {
            position: relative;
            display: flex;
            align-items: flex-end;
            justify-content: start;
        }

        .swiper-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }

        .swiper-slide:hover img {
            transform: scale(1.05);
        }

        .slide-text {
            background: rgba(0, 0, 0, 0.5);
            color: #fff;
            padding: 20px 25px;
            border-radius: 15px;
            margin: 30px;
            max-width: 500px;
            animation: fadeUp 0.8s ease forwards;
        }

        .slide-text h2 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            font-weight: 700;
        }

        .slide-text p {
            font-size: 1.1rem;
        }

        .swiper-button-next,
        .swiper-button-prev {
            color: #fff;
            background: rgba(0, 0, 0, 0.5);
            width: 50px;
            height: 50px;
            border-radius: 50%;
        }

        .swiper-pagination-bullet {
            background: rgba(255, 255, 255, 0.41);
            width: 14px;
            height: 14px;
            opacity: 1;
        }

        .swiper-pagination-bullet-active {
            background: #ff0048ff;
        }

        .swiper-button-next:after,
        .swiper-button-prev:after {
            font-size: 23px;
            font-weight: bold;
        }


        .swiper-horizontal>.swiper-pagination-bullets,
        .swiper-pagination-bullets.swiper-pagination-horizontal,
        .swiper-pagination-custom,
        .swiper-pagination-fraction {
            width: unset;
            left: unset;
        }

        .swiper-pagination {
            background-color: rgba(0, 0, 0, 0.4);
            padding: 5px 10px;
            border-radius: 12px;
            bottom: 10px !important;
            width: fit-content !important;
            left: 50% !important;
            transform: translateX(-50%);
        }

        .swiper-pagination-bullet {
            background-color: #fff;
            opacity: 0.8;
        }

        .swiper-pagination-bullet-active {
            background-color: #6a00ffff;
            opacity: 1;
        }


        body {
            background-color: #f8f9fa;
            font-family: 'Vazirmatn', sans-serif;
            font-variant-numeric: normal;
        }

        table,
        td,
        th {
            font-family: 'Vazirmatn', sans-serif;
        }

        .slider-container {
            position: relative;
            overflow: hidden;
            height: 500px;
        }

        .slider {
            display: flex;
            transition: transform 1s ease-in-out;
            height: 100%;
        }

        .slide {
            min-width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .slide-content {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 2rem;
            background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
            color: white;
        }

        .movie-card {
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 350px;
        }

        .movie-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .movie-card-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
            opacity: 0;
            transition: opacity 0.3s ease;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 1rem;
            color: white;
        }

        .movie-card:hover .movie-card-overlay {
            opacity: 1;
        }

        /* حالت موبایل و تبلت: overlay همیشه visible */
        @media (max-width: 768px) {
            .movie-card .movie-card-overlay {
                opacity: 1;
            }
        }


        .movie-rating-container {
            position: absolute;
            top: 10px;
            right: 10px;
            display: flex;
            flex-direction: column;
            /* یکی بیاد زیر اون یکی */
            gap: 6px;
            /* فاصله بین‌شون */
        }

        .movie-rating-badge {
            background-color: rgba(245, 158, 11, 0.9);
            color: white;
            border-radius: 4px;
            padding: 4px 8px;
            font-weight: bold;
            font-size: 14px;
            display: flex;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }


        .TMDb-icon {
            font-weight: bold;
            font-style: normal;
            background-color: #f5c518;
            color: #000;
            padding: 1px 4px;
            border-radius: 2px;
            margin-left: 4px;
            font-size: 10px;
        }

        .wave-divider {
            position: relative;
            margin-bottom: -1px;
        }

        .modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 50;
        }

        .modal {
            background-color: white;
            border-radius: 12px;
            max-width: 90%;
            max-height: 90%;
            overflow-y: auto;
            position: relative;
            animation: modalFadeIn 0.3s ease;
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .otp-input {
            width: 50px;
            height: 50px;
            text-align: center;
            font-size: 1.5rem;
            margin: 0 5px;
            border: 2px solid #ddd;
            border-radius: 8px;
        }

        .otp-input:focus {
            border-color: #3b82f6;
            outline: none;
        }

        .seat {
            width: 40px;
            height: 40px;
            margin: 5px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            position: relative;
        }

        .seat input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
        }

        .seat-label {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            background-color: #e5e7eb;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .seat input:checked+.seat-label {
            background-color: #3b82f6;
            color: white;
        }

        .seat input:disabled+.seat-label {
            background-color: #ef4444;
            color: white;
            cursor: not-allowed;
        }

        .flash-message {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #10b981;
            color: white;
            padding: 1rem 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 100;
            animation: flashIn 0.3s ease, flashOut 0.3s ease 2.7s forwards;
            display: none;
        }

        @media (max-width: 480px) {
            span.flash-message-text {
                font-size: 15px;
            }

            .flash-message {
                width: 70%;
            }
        }


        @keyframes flashIn {
            from {
                opacity: 0;
                transform: translate(-50%, -20px);
            }

            to {
                opacity: 1;
                transform: translate(-50%, 0);
            }
        }

        @keyframes flashOut {
            from {
                opacity: 1;
                transform: translate(-50%, 0);
            }

            to {
                opacity: 0;
                transform: translate(-50%, -20px);
            }
        }

        .movie-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.6s ease forwards;
        }

        @media (max-width: 768px) {
            .movie-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* @media (max-width: 480px) {
            .movie-grid {
                grid-template-columns: 1fr;
            }
        } */

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .movie-card {
            animation: cardAppear 0.5s ease forwards;
            opacity: 0;
            transform: translateY(20px);
        }

        @keyframes cardAppear {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .table-row {
            animation: rowAppear 0.3s ease forwards;
            opacity: 0;
        }

        @keyframes rowAppear {
            to {
                opacity: 1;
            }
        }

        .day-selector {
            display: flex;
            overflow-x: auto;
            gap: 10px;
            padding: 10px 0;
        }

        .day-item {
            min-width: 80px;
            padding: 10px;
            text-align: center;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .day-item.active {
            background-color: #3b82f6;
            color: white;
        }

        .showtime-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .showtime-card {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 1rem;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .showtime-card:hover {
            border-color: #3b82f6;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .box-office-table {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .social-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .social-icon:hover {
            background-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-3px);
        }

        .footer-logo {
            font-size: 28px;
            font-weight: 800;
            background: linear-gradient(45deg, #60a5fa, #93c5fd);

            /* برای همه مرورگرها */
            background-clip: text;

            /* برای WebKit (Chrome, Safari) */
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;

            /* fallback برای بقیه */
            color: transparent;

            margin-bottom: 10px;
        }


        .footer-link {
            transition: all 0.2s ease;
            position: relative;
        }

        .footer-link:before {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 1px;
            background-color: #60a5fa;
            transition: width 0.3s ease;
        }

        .footer-link:hover:before {
            width: 100%;
        }

        .showtime-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        /* فاصله بیشتر و ستون کمتر روی موبایل */
        @media (max-width: 480px) {
            .grid.grid-cols-10 {
                grid-template-columns: repeat(5, 1fr);
                /* نصف ستون‌ها */
                gap: 0.5rem;
                /* فاصله بین صندلی‌ها */
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header class="shadow-md sticky top-0 z-40 bg-white/20 backdrop-blur-md">
        <div class="container mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center">
                <div class="text-2xl font-bold text-blue-600 py-1 px-3 rounded-lg shadow-sm bg-white/40">سینما تیکت
                </div>
            </div>

            <div class="flex-1 mx-3 w-full md:mx-8">
                <div class="relative w-full">
                    <input type="text" placeholder="جستجو..."
                        class="w-full py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white/40">
                    <button class="absolute left-2 top-1/2 transform -translate-y-1/2 text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- دسکتاپ -->
            <div class="hidden md:block">
                <button id="loginBtn"
                    class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition duration-300">
                    ورود / ثبت نام
                </button>
            </div>

            <!-- موبایل -->
            <div class="block md:hidden">
                <button id="loginBtnMobile"
                    class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-lg transition duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 12h14M12 5l7 7-7 7" />
                    </svg>
                </button>
            </div>

        </div>
    </header>

    <div class="px-4 sm:px-6 lg:px-5 mt-10">
        <div class="swiper mySwiper rounded-2xl overflow-hidden relative w-full">
            <div class="swiper-wrapper">
                <div class="swiper-slide relative">
                    <img src="https://static.cdn.asset.cinematicket.org/media/image/2025/8/f8c0c535-ac39-4f76-8289-88da8171ef78_desktop.jpeg"
                        alt="" class="w-full object-cover rounded-2xl">

                    <div
                        class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent rounded-2xl">
                    </div>

                    <div class="absolute bottom-8 left-8 text-white p-4 max-w-lg">
                        <h2 class="text-3xl font-bold mb-2">جدیدترین فیلم‌های روز سینما</h2>
                        <p class="text-lg">بلیط فیلم مورد علاقه خود را همین حالا رزرو کنید</p>
                    </div>
                </div>

                <div class="swiper-slide relative">
                    <img src="https://static.cdn.asset.cinematicket.org/media/image/2025/8/a0794ba1-90e4-4c96-906f-a545bcaab48c_desktop.jpeg"
                        alt="" class="w-full h-[500px] object-cover rounded-2xl">

                    <div
                        class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent rounded-2xl">
                    </div>

                    <div class="absolute bottom-8 left-8 text-white p-4 max-w-lg">
                        <h2 class="text-3xl font-bold mb-2">جشنواره فیلم تابستانی</h2>
                        <p class="text-lg">با تخفیف ویژه از سینماهای سراسر کشور</p>
                    </div>
                </div>

                <div class="swiper-slide relative">
                    <img src="https://static.cdn.asset.cinematicket.org/media/image/2025/8/010bcfc5-17ab-44dd-85c7-a23e4f88d6aa_desktop.jpeg"
                        alt="" class="w-full h-[500px] object-cover rounded-2xl">

                    <div
                        class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent rounded-2xl">
                    </div>

                    <div class="absolute bottom-8 left-8 text-white p-4 max-w-lg">
                        <h2 class="text-3xl font-bold mb-2">جشنواره فیلم تابستانی</h2>
                        <p class="text-lg">با تخفیف ویژه از سینماهای سراسر کشور</p>
                    </div>
                </div>
            </div>

            <!-- دکمه‌ها -->
            <div class="swiper-button-next text-white"></div>
            <div class="swiper-button-prev text-white"></div>
            <!-- نقاط -->
            <div class="swiper-pagination"></div>
        </div>
    </div>

    <!-- اسکریپت Swiper -->
    <script>
        const swiper = new Swiper(".mySwiper", {
            loop: true,
            grabCursor: true,
            slidesPerView: 1,
            spaceBetween: 20,
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
        });
    </script>




    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row gap-8">
            <!-- Movies Grid -->
            <div class="md:w-2/3">
                <h2 class="text-2xl font-bold mb-6 text-center relative">
                    <span class="relative z-10">فیلم‌های در حال اکران</span>
                    <svg class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 -z-10" width="300"
                        height="50" viewBox="0 0 300 50">
                        <path d="M10,25 Q75,10 150,25 Q225,40 290,25" stroke="#3b82f6" stroke-width="5" fill="none" />
                    </svg>
                </h2>

                <div class="movie-grid">
                    <div class="movie-card" style="animation-delay: 0.1s"
                        onclick="openMovieModal('آخرین داستان', 'اشکان رهگذر', 'انیمیشن', '۸.۵')">
                        <div class="movie-rating-container">
                            <div class="movie-rating-badge">
                                <span class="TMDb-icon">TMDb</span>
                                ۸.۵
                            </div>
                            <div class="movie-rating-badge">
                                <span class="rotten-icon">❤️</span>
                                ۳.۸
                            </div>
                        </div>

                        <img src="assets/img/3.jpg" alt="آخرین داستان" class="w-full h-full object-cover">
                        <div class="movie-card-overlay">
                            <h3 class="font-bold text-lg">آخرین داستان</h3>
                            <p class="text-sm mb-2">کارگردان: اشکان رهگذر</p>
                            <div class="flex justify-between items-center mt-1">
                                <span class="text-sm">انیمیشن</span>
                            </div>
                            <p class="text-xs mt-2">اقتباسی آزاد از داستان‌های شاهنامه فردوسی</p>
                            <button
                                class="mt-3 bg-blue-600 hover:bg-blue-700 text-white py-1 px-3 rounded-lg text-sm">خرید
                                بلیط</button>
                        </div>
                    </div>

                    <div class="movie-card" style="animation-delay: 0.2s;"
                        onclick="openMovieModal('مطرب', 'مصطفی کیایی', 'کمدی', '۷.۸')">
                        <div class="movie-rating-container">
                            <div class="movie-rating-badge">
                                <span class="TMDb-icon">TMDb</span>
                                ۷.۸
                            </div>
                            <div class="movie-rating-badge">
                                <span class="rotten-icon">❤️</span>
                                ۳.۸
                            </div>
                        </div>
                        <img src="assets/img/2.jpg" alt="مطرب" class="w-full h-full object-cover">
                        <div class="movie-card-overlay">
                            <h3 class="font-bold text-lg">مطرب</h3>
                            <p class="text-sm mb-2">کارگردان: مصطفی کیایی</p>
                            <div class="flex justify-between items-center mt-1">
                                <span class="text-sm">کمدی</span>
                            </div>
                            <p class="text-xs mt-2">داستان خواننده‌ای که بعد از سال‌ها ممنوع‌الکاری...</p>
                            <button
                                class="mt-3 bg-blue-600 hover:bg-blue-700 text-white py-1 px-3 rounded-lg text-sm">خرید
                                بلیط</button>
                        </div>
                    </div>

                    <div class="movie-card" style="animation-delay: 0.3s;"
                        onclick="openMovieModal('دینامیت', 'مسعود اطیابی', 'کمدی', '۸.۲')">
                        <div class="movie-rating-container">
                            <div class="movie-rating-badge">
                                <span class="TMDb-icon">TMDb</span>
                                ۸.۲
                            </div>
                            <div class="movie-rating-badge">
                                <span class="rotten-icon">❤️</span>
                                ۳.۸
                            </div>
                        </div>

                        <img src="assets/img/1.jpg" alt="دینامیت" class="w-full h-full object-cover">
                        <div class="movie-card-overlay">
                            <h3 class="font-bold text-lg">دینامیت</h3>
                            <p class="text-sm mb-2">کارگردان: مسعود اطیابی</p>
                            <div class="flex justify-between items-center mt-1">
                                <span class="text-sm">کمدی</span>
                            </div>
                            <p class="text-xs mt-2">داستان دو دوست که درگیر ماجراهای خنده‌دار می‌شوند</p>
                            <button
                                class="mt-3 bg-blue-600 hover:bg-blue-700 text-white py-1 px-3 rounded-lg text-sm">خرید
                                بلیط</button>
                        </div>
                    </div>

                    <div class="movie-card" style="animation-delay: 0.4s;"
                        onclick="openMovieModal('قهرمان', 'اصغر فرهادی', 'درام', '۹.۱')">
                        <div class="movie-rating-container">
                            <div class="movie-rating-badge">
                                <span class="TMDb-icon">TMDb</span>
                                ۹.۱
                            </div>
                            <div class="movie-rating-badge">
                                <span class="rotten-icon">❤️</span>
                                ۳.۸
                            </div>
                        </div>

                        <img src="assets/img/4.jpg" alt="قهرمان" class="w-full h-full object-cover">
                        <div class="movie-card-overlay">
                            <h3 class="font-bold text-lg">قهرمان</h3>
                            <p class="text-sm mb-2">کارگردان: اصغر فرهادی</p>
                            <div class="flex justify-between items-center mt-1">
                                <span class="text-sm">درام</span>
                            </div>
                            <p class="text-xs mt-2">داستان مردی که در مرخصی زندان تلاش می‌کند...</p>
                            <button
                                class="mt-3 bg-blue-600 hover:bg-blue-700 text-white py-1 px-3 rounded-lg text-sm">خرید
                                بلیط</button>
                        </div>
                    </div>

                    <div class="movie-card" style="animation-delay: 0.5s;"
                        onclick="openMovieModal('روز صفر', 'سعید ملکان', 'اکشن', '۸.۷')">
                        <div class="movie-rating-container">
                            <div class="movie-rating-badge">
                                <span class="TMDb-icon">TMDb</span>
                                ۸.۷
                            </div>
                            <div class="movie-rating-badge">
                                <span class="rotten-icon">❤️</span>
                                ۳.۸
                            </div>
                        </div>

                        <img src="assets/img/5.jpg" alt="روز صفر" class="w-full h-full object-cover">
                        <div class="movie-card-overlay">
                            <h3 class="font-bold text-lg">روز صفر</h3>
                            <p class="text-sm mb-2">کارگردان: سعید ملکان</p>
                            <div class="flex justify-between items-center mt-1">
                                <span class="text-sm">اکشن</span>
                            </div>
                            <p class="text-xs mt-2">داستان مأموری که درگیر عملیات پیچیده‌ای می‌شود</p>
                            <button
                                class="mt-3 bg-blue-600 hover:bg-blue-700 text-white py-1 px-3 rounded-lg text-sm">خرید
                                بلیط</button>
                        </div>
                    </div>

                    <div class="movie-card" style="animation-delay: 0.6s;"
                        onclick="openMovieModal('ابلق', 'نرگس آبیار', 'درام', '۸.۳')">
                        <div class="movie-rating-container">
                            <div class="movie-rating-badge">
                                <span class="TMDb-icon">TMDb</span>
                                ۸.۳
                            </div>
                            <div class="movie-rating-badge">
                                <span class="rotten-icon">❤️</span>
                                ۳.۸
                            </div>
                        </div>

                        <img src="assets/img/6.jpg" alt="ابلق" class="w-full h-full object-cover">
                        <div class="movie-card-overlay">
                            <h3 class="font-bold text-lg">ابلق</h3>
                            <p class="text-sm mb-2">کارگردان: نرگس آبیار</p>
                            <div class="flex justify-between items-center mt-1">
                                <span class="text-sm">درام</span>
                            </div>
                            <p class="text-xs mt-2">داستان زنی که با چالش‌های زندگی مواجه می‌شود</p>
                            <button
                                class="mt-3 bg-blue-600 hover:bg-blue-700 text-white py-1 px-3 rounded-lg text-sm">خرید
                                بلیط</button>
                        </div>
                    </div>

                    <div class="movie-card" style="animation-delay: 0.6s;"
                        onclick="openMovieModal('مردعنکوتی', 'جاستین تامپسون', 'علمی تخیلی', '۸.۳')">
                        <div class="movie-rating-container">
                            <div class="movie-rating-badge">
                                <span class="TMDb-icon">TMDb</span>
                                ۸.۳
                            </div>
                            <div class="movie-rating-badge">
                                <span class="rotten-icon">❤️</span>
                                ۳.۸
                            </div>
                        </div>

                        <img src="assets/img/8.svg" alt="مردعنکوتی" class="w-full h-full object-cover">
                        <div class="movie-card-overlay">
                            <h3 class="font-bold text-lg">مردعنکوتی</h3>
                            <p class="text-sm mb-2">کارگردان: جاستین تامپسون</p>
                            <div class="flex justify-between items-center mt-1">
                                <span class="text-sm">علمی تخیلی</span>
                            </div>
                            <p class="text-xs mt-2">داستان مردعنکوتی که با چالش‌های زندگی مواجه می‌شود</p>
                            <button
                                class="mt-3 bg-blue-600 hover:bg-blue-700 text-white py-1 px-3 rounded-lg text-sm">خرید
                                بلیط</button>
                        </div>
                    </div>

                    <div class="movie-card" style="animation-delay: 0.6s;"
                        onclick="openMovieModal('بتمن', 'کریستوفر نولان', 'درام', '۸.۳')">
                        <div class="movie-rating-container">
                            <div class="movie-rating-badge">
                                <span class="TMDb-icon">TMDb</span>
                                ۸.۳
                            </div>
                            <div class="movie-rating-badge">
                                <span class="rotten-icon">❤️</span>
                                ۳.۸
                            </div>
                        </div>
                        <img src="assets/img/7.svg" alt="بتمن" class="w-full h-full object-cover">
                        <div class="movie-card-overlay">
                            <h3 class="font-bold text-lg">بتمن</h3>
                            <p class="text-sm mb-2">کارگردان: کریستوفر نولان</p>
                            <div class="flex justify-between items-center mt-1">
                                <span class="text-sm">درام</span>
                            </div>
                            <p class="text-xs mt-2">داستان مردی که با چالش‌های زندگی مواجه می‌شود</p>
                            <button
                                class="mt-3 bg-blue-600 hover:bg-blue-700 text-white py-1 px-3 rounded-lg text-sm">خرید
                                بلیط</button>
                        </div>
                    </div>

                    <div class="movie-card" style="animation-delay: 0.6s;"
                        onclick="openMovieModal('انتقام جویان', 'برادران روسو', 'علمی تخیلی', '۸.۳')">
                        <div class="movie-rating-container">
                            <div class="movie-rating-badge">
                                <span class="TMDb-icon">TMDb</span>
                                ۸.۳
                            </div>
                            <div class="movie-rating-badge">
                                <span class="rotten-icon">❤️</span>
                                ۳.۸
                            </div>
                        </div>
                        <img src="assets/img/9.svg" alt="انتقام جویان" class="w-full h-full object-cover">
                        <div class="movie-card-overlay">
                            <h3 class="font-bold text-lg">انتقام جویان</h3>
                            <p class="text-sm mb-2">کارگردان: برادران روسو</p>
                            <div class="flex justify-between items-center mt-1">
                                <span class="text-sm">درام</span>
                            </div>
                            <p class="text-xs mt-2">داستان گروهی از قهرمانان که با چالش‌های نجات دنیا مواجه می‌شود</p>
                            <button
                                class="mt-3 bg-blue-600 hover:bg-blue-700 text-white py-1 px-3 rounded-lg text-sm">خرید
                                بلیط</button>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Box Office Table -->
            <?php if (!empty($result)):
                ?>
                <div class="md:w-1/3 bg-white rounded-lg shadow-lg p-6 animate-fadeIn box-office-table h-dvh mt-12">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold">جدول فروش</h2>
                        <span class="text-sm text-gray-500">به روز رسانی: امروز</span>
                    </div>
                    <div class="overflow-x-auto flex-grow">
                        <table class="w-full">
                            <tbody>
                                <?php
                                $rank = 1;
                                foreach ($result as $row):
                                    if ($rank > 9)
                                        break;
                                    ?>
                                    <tr class="border-b border-gray-200 table-row"
                                        style="animation-delay: <?= $rank * 0.1 ?>s;">
                                        <td class="py-3"><img src="<?= $row['img'] ?>" alt=""
                                                style="max-width: 50px; border-radius: 5px;" loading="lazy"></td>
                                        <td class="py-3"><?= enToFaNumber($rank) . ' . ' ?></td>
                                        <td class="py-3"><?= htmlspecialchars($row['movie']) ?></td>
                                        <td class="py-3"><?= $row['sales'] ?></td>
                                    </tr>
                                    <?php
                                    $rank++;
                                endforeach;
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 text-center">
                        <button
                            class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition duration-300">
                            مشاهده کامل جدول فروش
                        </button>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Wave Divider -->
    <div class="wave-divider">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 100" fill="#1e3a8a">
            <path
                d="M0,64L48,69.3C96,75,192,85,288,80C384,75,480,53,576,53.3C672,53,768,75,864,80C960,85,1056,75,1152,64C1248,53,1344,43,1392,37.3L1440,32L1440,100L1392,100C1344,100,1248,100,1152,100C1056,100,960,100,864,100C768,100,672,100,576,100C480,100,384,100,288,100C192,100,96,100,48,100L0,100Z">
            </path>
        </svg>
    </div>

    <!-- Footer -->
    <footer class="bg-blue-900 text-white py-8">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="footer-logo">سینما تیکت</div>
                    <div class="flex items-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-300 mr-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
                        </svg>
                        <span>سامانه خرید آنلاین بلیط سینما</span>
                    </div>
                    <p class="text-blue-200 mb-4">با سینما تیکت به راحتی و با چند کلیک بلیط فیلم مورد علاقه خود را تهیه
                        کنید و از تماشای آن لذت ببرید.</p>
                    <div class="flex space-x-4 space-x-reverse">
                        <a href="#" class="social-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </a>
                        <a href="#" class="social-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </a>
                        <a href="#" class="social-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </a>
                        <a href="#" class="social-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                            </svg>
                        </a>
                    </div>
                    <div class="mt-4 pt-4 border-t border-blue-800">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-300 ml-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <span>۰۲۱-۱۲۳۴۵۶۷۸</span>
                        </div>
                        <div class="flex items-center mt-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-300 ml-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span>info@cinematicket.ir</span>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-xl font-bold mb-4">پربازدیدترین فیلم‌های هفته</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-blue-200 hover:text-white footer-link">آخرین داستان</a></li>
                        <li><a href="#" class="text-blue-200 hover:text-white footer-link">مطرب</a></li>
                        <li><a href="#" class="text-blue-200 hover:text-white footer-link">دینامیت</a></li>
                        <li><a href="#" class="text-blue-200 hover:text-white footer-link">قهرمان</a></li>
                        <li><a href="#" class="text-blue-200 hover:text-white footer-link">روز صفر</a></li>
                        <li><a href="#" class="text-blue-200 hover:text-white footer-link">ابلق</a></li>
                        <li><a href="#" class="text-blue-200 hover:text-white footer-link">بدون قرار قبلی</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-xl font-bold mb-4">پربازدیدترین فیلم‌های روز</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-blue-200 hover:text-white footer-link">قهرمان</a></li>
                        <li><a href="#" class="text-blue-200 hover:text-white footer-link">دینامیت</a></li>
                        <li><a href="#" class="text-blue-200 hover:text-white footer-link">گشت ارشاد ۳</a></li>
                        <li><a href="#" class="text-blue-200 hover:text-white footer-link">مرد عنکبوتی: راهی به خانه
                                نیست</a></li>
                        <li><a href="#" class="text-blue-200 hover:text-white footer-link">شهر گربه‌ها</a></li>
                        <li><a href="#" class="text-blue-200 hover:text-white footer-link">لامینور</a></li>
                        <li><a href="#" class="text-blue-200 hover:text-white footer-link">تک تیرانداز</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-xl font-bold mb-4">دسترسی سریع</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-blue-200 hover:text-white footer-link">سینماهای تهران</a></li>
                        <li><a href="#" class="text-blue-200 hover:text-white footer-link">فیلم‌های روز</a></li>
                        <li><a href="#" class="text-blue-200 hover:text-white footer-link">جدول فروش</a></li>
                        <li><a href="#" class="text-blue-200 hover:text-white footer-link">اخبار سینما</a></li>
                        <li><a href="#" class="text-blue-200 hover:text-white footer-link">جشنواره‌های فیلم</a></li>
                        <li><a href="#" class="text-blue-200 hover:text-white footer-link">سوالات متداول</a></li>
                        <li><a href="#" class="text-blue-200 hover:text-white footer-link">تماس با ما</a></li>
                    </ul>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-blue-800">
                <div class="flex flex-wrap justify-between items-center">
                    <div class="mb-4 md:mb-0">
                        <h4 class="font-bold mb-2">دانلود اپلیکیشن سینما تیکت</h4>
                        <div class="flex space-x-4 space-x-reverse">
                            <a href="#"
                                class="bg-blue-800 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition duration-300 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                دانلود از بازار
                            </a>
                            <a href="#"
                                class="bg-blue-800 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition duration-300 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                                </svg>
                                دانلود از گوگل پلی
                            </a>
                        </div>
                    </div>
                    <div class="flex space-x-4 space-x-reverse">
                        <div class="bg-blue-800 p-2 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <div class="bg-blue-800 p-2 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="bg-blue-800 p-2 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-blue-800 text-center text-blue-300">
                <p>تمامی حقوق این سایت متعلق به سینما تیکت می‌باشد. &copy; ۱۴۰۲</p>
            </div>
        </div>
    </footer>

    <!-- Login Modal -->
    <div id="loginModal" class="modal-backdrop px-3">
        <div class="modal w-full max-w-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold">ورود / ثبت نام</h3>
                <button id="closeLoginModal" class="text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div id="phoneInputStep">
                <p class="text-gray-600 mb-4">لطفاً شماره موبایل خود را وارد کنید</p>
                <div class="mb-4">
                    <label for="phone" class="block text-gray-700 mb-2">شماره موبایل</label>
                    <input type="tel" id="phone"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="مثال: ۰۹۱۲۳۴۵۶۷۸۹" dir="ltr">
                </div>
                <button id="sendCodeBtn"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition duration-300">دریافت
                    کد تأیید</button>
            </div>

            <div id="otpInputStep" class="hidden">
                <p class="text-gray-600 mb-4">کد تأیید ارسال شده به شماره <span id="userPhone" class="font-bold"></span>
                    را وارد کنید</p>
                <div class="flex justify-center space-x-2 mb-4 flex-row-reverse">
                    <input type="text" class="otp-input" maxlength="1" data-index="0">
                    <input type="text" class="otp-input" maxlength="1" data-index="1">
                    <input type="text" class="otp-input" maxlength="1" data-index="2">
                    <input type="text" class="otp-input" maxlength="1" data-index="3">
                    <input type="text" class="otp-input" maxlength="1" data-index="4">
                </div>
                <div class="flex justify-between items-center mb-4">
                    <button id="resendCodeBtn" class="text-blue-600 hover:text-blue-800">ارسال مجدد کد</button>
                    <span id="timer" class="text-gray-500">۰۲:۰۰</span>
                </div>
                <button id="verifyCodeBtn"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition duration-300">تأیید
                    و ورود</button>
            </div>
        </div>
    </div>

    <!-- Movie Modal -->
    <div id="movieModal" class="modal-backdrop px-5">
        <div class="modal w-full max-w-4xl">
            <div class="flex justify-between items-center bg-blue-600 text-white p-4 rounded-t-lg">
                <h3 class="text-xl font-bold" id="movieModalTitle">جزئیات فیلم</h3>
                <button id="closeMovieModal" class="text-white hover:text-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="p-6">
                <!-- Movie Info Section -->
                <div class="flex flex-col md:flex-row gap-6 mb-6">
                    <div class="md:w-1/3">
                        <img id="moviePoster" class="w-full h-full bg-gray-200 rounded-lg object-cover">
                    </div>
                    <div class="md:w-2/3">
                        <h2 id="movieName" class="text-2xl font-bold mb-2">نام فیلم</h2>
                        <div class="flex items-center mb-2">
                            <span class="text-gray-600 ml-2">کارگردان:</span>
                            <span id="movieDirector">نام کارگردان</span>
                        </div>
                        <div class="flex items-center mb-2">
                            <span class="text-gray-600 ml-2">ژانر:</span>
                            <span id="movieGenre">ژانر فیلم</span>
                        </div>
                        <div class="flex items-center mb-4">
                            <span class="text-gray-600 ml-2">امتیاز:</span>
                            <span id="movieRating" class="bg-yellow-500 text-white px-2 py-1 rounded">۸.۵</span>
                            <span class="mr-2 text-gray-600">(۱۲۳۴ رأی)</span>
                        </div>
                        <p id="movieDescription" class="text-gray-700 mb-4">خلاصه داستان فیلم در اینجا نمایش داده
                            می‌شود...</p>
                    </div>
                </div>

                <!-- Days Selection -->
                <div class="mb-6">
                    <h3 class="text-lg font-bold mb-3">انتخاب روز</h3>
                    <div class="day-selector">
                        <div class="day-item active" data-day="شنبه">
                            <div class="font-bold">شنبه</div>
                            <div class="text-sm">۱۵ مرداد</div>
                        </div>
                        <div class="day-item" data-day="یکشنبه">
                            <div class="font-bold">یکشنبه</div>
                            <div class="text-sm">۱۶ مرداد</div>
                        </div>
                        <div class="day-item" data-day="دوشنبه">
                            <div class="font-bold">دوشنبه</div>
                            <div class="text-sm">۱۷ مرداد</div>
                        </div>
                        <div class="day-item" data-day="سه‌شنبه">
                            <div class="font-bold">سه‌شنبه</div>
                            <div class="text-sm">۱۸ مرداد</div>
                        </div>
                        <div class="day-item" data-day="چهارشنبه">
                            <div class="font-bold">چهارشنبه</div>
                            <div class="text-sm">۱۹ مرداد</div>
                        </div>
                    </div>
                </div>

                <!-- Cinema & Showtimes -->
                <div>
                    <h3 class="text-lg font-bold mb-2">سینما آزادی</h3>
                    <p class="text-gray-600 mb-4">آدرس: خیابان استاد معین، تقاطع هاشمی</p>

                    <div class="showtime-grid">
                        <div class="showtime-card" onclick="openSeatSelectionModal()">
                            <div class="showtime-title">
                                <div class="font-bold">سانس اول</div>
                                <div class="text-lg">۱۲:۱۵</div>
                            </div>
                            <div class="text-center mb-2 text-gray-700">۱۱۰,۰۰۰ تومان</div>
                            <div class="text-center">
                                <button
                                    class="bg-blue-600 hover:bg-blue-700 text-white py-1 px-3 rounded-lg text-sm">انتخاب
                                    صندلی</button>
                            </div>
                            <div class="mt-2 pt-2 border-t border-gray-200 text-center text-gray-500 text-sm">
                                سالن یک
                            </div>
                        </div>

                        <div class="showtime-card" onclick="openSeatSelectionModal()">
                            <div class="showtime-title">
                                <div class="font-bold">سانس دوم</div>
                                <div class="text-lg">۱۵:۳۰</div>
                            </div>
                            <div class="text-center mb-2 text-gray-700">۱۲۰,۰۰۰ تومان</div>
                            <div class="text-center">
                                <button
                                    class="bg-blue-600 hover:bg-blue-700 text-white py-1 px-3 rounded-lg text-sm">انتخاب
                                    صندلی</button>
                            </div>
                            <div class="mt-2 pt-2 border-t border-gray-200 text-center text-gray-500 text-sm">
                                سالن دو
                            </div>
                        </div>

                        <div class="showtime-card" onclick="openSeatSelectionModal()">
                            <div class="showtime-title">
                                <div class="font-bold">سانس سوم</div>
                                <div class="text-lg">۱۸:۰۰</div>
                            </div>
                            <div class="text-center mb-2 text-gray-700">۱۳۰,۰۰۰ تومان</div>
                            <div class="text-center">
                                <button
                                    class="bg-blue-600 hover:bg-blue-700 text-white py-1 px-3 rounded-lg text-sm">انتخاب
                                    صندلی</button>
                            </div>
                            <div class="mt-2 pt-2 border-t border-gray-200 text-center text-gray-500 text-sm">
                                سالن یک
                            </div>
                        </div>

                        <div class="showtime-card" onclick="openSeatSelectionModal()">
                            <div class="showtime-title">
                                <div class="font-bold">سانس چهارم</div>
                                <div class="text-lg">۲۰:۴۵</div>
                            </div>
                            <div class="text-center mb-2 text-gray-700">۱۴۰,۰۰۰ تومان</div>
                            <div class="text-center">
                                <button
                                    class="bg-blue-600 hover:bg-blue-700 text-white py-1 px-3 rounded-lg text-sm">انتخاب
                                    صندلی</button>
                            </div>
                            <div class="mt-2 pt-2 border-t border-gray-200 text-center text-gray-500 text-sm">
                                سالن سه
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Seat Selection Modal -->
    <div id="seatSelectionModal" class="modal-backdrop px-4 sm:px-10">
        <div class="modal w-full max-w-md sm:max-w-2xl mx-auto my-8 bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="flex justify-between items-center bg-blue-600 text-white p-4 rounded-t-lg">
                <h3 class="text-xl font-bold">انتخاب صندلی</h3>
                <button id="closeSeatModal" class="text-white hover:text-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- محتوای modal با scroll داخلی -->
            <div class="p-4 sm:p-6 max-h-[80vh] overflow-y-auto">
                <!-- پرده نمایش -->
                <div class="mb-6 text-center">
                    <div
                        class="w-3/4 h-10 bg-gray-300 mx-auto mb-4 rounded-lg flex items-center justify-center text-gray-700">
                        پرده نمایش
                    </div>

                    <!-- صندلی‌ها -->
                    <div class="grid grid-cols-5 sm:grid-cols-10 gap-1 mb-6">
                        <!-- مثال row -->
                        <div class="seat">
                            <input type="checkbox" id="seat-1-1">
                            <label for="seat-1-1" class="seat-label">۱</label>
                        </div>
                        <div class="seat">
                            <input type="checkbox" id="seat-1-2">
                            <label for="seat-1-2" class="seat-label">۲</label>
                        </div>
                        <div class="seat">
                            <input type="checkbox" id="seat-1-3">
                            <label for="seat-1-3" class="seat-label">۳</label>
                        </div>
                        <div class="seat">
                            <input type="checkbox" id="seat-1-4">
                            <label for="seat-1-4" class="seat-label">۴</label>
                        </div>
                        <div class="seat">
                            <input type="checkbox" id="seat-1-5">
                            <label for="seat-1-5" class="seat-label">۵</label>
                        </div>
                        <div class="seat">
                            <input type="checkbox" id="seat-1-6">
                            <label for="seat-1-6" class="seat-label">۶</label>
                        </div>
                        <div class="seat">
                            <input type="checkbox" id="seat-1-7">
                            <label for="seat-1-7" class="seat-label">۷</label>
                        </div>
                        <div class="seat">
                            <input type="checkbox" id="seat-1-8">
                            <label for="seat-1-8" class="seat-label">۸</label>
                        </div>
                        <div class="seat">
                            <input type="checkbox" id="seat-1-9">
                            <label for="seat-1-9" class="seat-label">۹</label>
                        </div>
                        <div class="seat">
                            <input type="checkbox" id="seat-1-10">
                            <label for="seat-1-10" class="seat-label">۱۰</label>
                        </div>
                        <div class="seat">
                            <input type="checkbox" id="seat-2-1">
                            <label for="seat-2-1" class="seat-label">۱۱</label>
                        </div>
                        <div class="seat">
                            <input type="checkbox" id="seat-2-2">
                            <label for="seat-2-2" class="seat-label">۱۲</label>
                        </div>
                        <div class="seat">
                            <input type="checkbox" id="seat-2-3" disabled>
                            <label for="seat-2-3" class="seat-label">۱۳</label>
                        </div>
                        <div class="seat">
                            <input type="checkbox" id="seat-2-4" disabled>
                            <label for="seat-2-4" class="seat-label">۱۴</label>
                        </div>
                        <div class="seat">
                            <input type="checkbox" id="seat-2-5">
                            <label for="seat-2-5" class="seat-label">۱۵</label>
                        </div>
                        <div class="seat">
                            <input type="checkbox" id="seat-2-6">
                            <label for="seat-2-6" class="seat-label">۱۶</label>
                        </div>
                        <div class="seat">
                            <input type="checkbox" id="seat-2-7">
                            <label for="seat-2-7" class="seat-label">۱۷</label>
                        </div>
                        <div class="seat">
                            <input type="checkbox" id="seat-2-8">
                            <label for="seat-2-8" class="seat-label">۱۸</label>
                        </div>
                        <div class="seat">
                            <input type="checkbox" id="seat-2-9">
                            <label for="seat-2-9" class="seat-label">۱۹</label>
                        </div>
                        <div class="seat">
                            <input type="checkbox" id="seat-2-10">
                            <label for="seat-2-10" class="seat-label">۲۰</label>
                        </div>
                        <div class="seat">
                            <input type="checkbox" id="seat-3-1">
                            <label for="seat-3-1" class="seat-label">۲۱</label>
                        </div>
                        <div class="seat">
                            <input type="checkbox" id="seat-3-2">
                            <label for="seat-3-2" class="seat-label">۲۲</label>
                        </div>
                        <div class="seat">
                            <input type="checkbox" id="seat-3-3">
                            <label for="seat-3-3" class="seat-label">۲۳</label>
                        </div>
                        <div class="seat">
                            <input type="checkbox" id="seat-3-4">
                            <label for="seat-3-4" class="seat-label">۲۴</label>
                        </div>
                        <div class="seat">
                            <input type="checkbox" id="seat-3-5">
                            <label for="seat-3-5" class="seat-label">۲۵</label>
                        </div>
                        <div class="seat">
                            <input type="checkbox" id="seat-3-6">
                            <label for="seat-3-6" class="seat-label">۲۶</label>
                        </div>
                        <div class="seat">
                            <input type="checkbox" id="seat-3-7" disabled>
                            <label for="seat-3-7" class="seat-label">۲۷</label>
                        </div>
                        <div class="seat">
                            <input type="checkbox" id="seat-3-8">
                            <label for="seat-3-8" class="seat-label">۲۸</label>
                        </div>
                        <div class="seat">
                            <input type="checkbox" id="seat-3-9">
                            <label for="seat-3-9" class="seat-label">۲۹</label>
                        </div>
                        <div class="seat">
                            <input type="checkbox" id="seat-3-10">
                            <label for="seat-3-10" class="seat-label">۳۰</label>
                        </div>
                        <!-- بقیه صندلی‌ها مشابه -->
                    </div>

                    <!-- توضیح رنگ‌ها -->
                    <div class="flex justify-center gap-4 mb-4 text-sm">
                        <div class="flex items-center gap-1">
                            <div class="w-3 h-3 bg-gray-300 rounded"></div> قابل انتخاب
                        </div>
                        <div class="flex items-center gap-1">
                            <div class="w-3 h-3 bg-blue-600 rounded"></div> انتخاب شده
                        </div>
                        <div class="flex items-center gap-1">
                            <div class="w-3 h-3 bg-red-600 rounded"></div> رزرو شده
                        </div>
                    </div>
                </div>

                <!-- پایین modal -->
                <div class="flex justify-between items-center border-t border-gray-200 pt-4">
                    <div>
                        <div class="text-gray-600 text-sm">مجموع قیمت:</div>
                        <div class="text-xl font-bold" id="totalPrice">۰ تومان</div>
                    </div>
                    <button id="reserveBtn"
                        class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded-lg transition duration-300">
                        رزرو
                    </button>
                </div>
            </div>
        </div>
    </div>



    <!-- Seat Selection Modal -->
    <div id="seatSelectionModal" class="modal-backdrop px-10">
        <div class="modal w-full max-w-2xl">
            <div class="flex justify-between items-center bg-blue-600 text-white p-4 rounded-t-lg">
                <h3 class="text-xl font-bold">انتخاب صندلی</h3>
                <button id="closeSeatModal" class="text-white hover:text-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="p-6">
                <div class="mb-6 text-center">
                    <div
                        class="w-3/4 h-10 bg-gray-300 mx-auto mb-8 rounded-lg flex items-center justify-center text-gray-700">
                        پرده نمایش</div>

                    <div class="grid grid-cols-10 gap-1 mb-8">
                        <!-- Row 1 -->
                        <div class="seat">
                            <input type="checkbox" id="seat-1-1">
                            <label for="seat-1-1" class="seat-label">۱</label>
                        </div>
                        <div class="seat">
                            <input type="checkbox" id="seat-1-2">
                            <label for="seat-1-2" class="seat-label">۲</label>
                        </div>
                        <div class="seat">
                            <input type="checkbox" id="seat-1-3">
                            <label for="seat-1-3" class="seat-label">۳</label>
                        </div>
                        <div class="seat">
                            <input type="checkbox" id="seat-1-4">
                            <label for="seat-1-4" class="seat-label">۴</label>
                        </div>
                        <div class="seat">
                            <input type="checkbox" id="seat-1-5">
                            <label for="seat-1-5" class="seat-label">۵</label>
                        </div>
                        <div class="seat">
                            <input type="checkbox" id="seat-1-6">
                            <label for="seat-1-6" class="seat-label">۶</label>
                        </div>
                        <div class="seat">
                            <input type="checkbox" id="seat-1-7">
                            <label for="seat-1-7" class="seat-label">۷</label>
                        </div>
                        <div class="seat">
                            <input type="checkbox" id="seat-1-8">
                            <label for="seat-1-8" class="seat-label">۸</label>
                        </div>
                        <div class="seat">
                            <input type="checkbox" id="seat-1-9">
                            <label for="seat-1-9" class="seat-label">۹</label>
                        </div>
                        <div class="seat">
                            <input type="checkbox" id="seat-1-10">
                            <label for="seat-1-10" class="seat-label">۱۰</label>
                        </div>

                        <!-- Row 2 -->
                        <div class="seat">
                            <input type="checkbox" id="seat-2-1">
                            <label for="seat-2-1" class="seat-label">۱۱</label>
                        </div>
                        <div class="seat">
                            <input type="checkbox" id="seat-2-2">
                            <label for="seat-2-2" class="seat-label">۱۲</label>
                        </div>
                        <div class="seat">
                            <input type="checkbox" id="seat-2-3" disabled>
                            <label for="seat-2-3" class="seat-label">۱۳</label>
                        </div>
                        <div class="seat">
                            <input type="checkbox" id="seat-2-4" disabled>
                            <label for="seat-2-4" class="seat-label">۱۴</label>
                        </div>
                        <div class="seat">
                            <input type="checkbox" id="seat-2-5">
                            <label for="seat-2-5" class="seat-label">۱۵</label>
                        </div>
                        <div class="seat">
                            <input type="checkbox" id="seat-2-6">
                            <label for="seat-2-6" class="seat-label">۱۶</label>
                        </div>
                        <div class="seat">
                            <input type="checkbox" id="seat-2-7">
                            <label for="seat-2-7" class="seat-label">۱۷</label>
                        </div>
                        <div class="seat">
                            <input type="checkbox" id="seat-2-8">
                            <label for="seat-2-8" class="seat-label">۱۸</label>
                        </div>
                        <div class="seat">
                            <input type="checkbox" id="seat-2-9">
                            <label for="seat-2-9" class="seat-label">۱۹</label>
                        </div>
                        <div class="seat">
                            <input type="checkbox" id="seat-2-10">
                            <label for="seat-2-10" class="seat-label">۲۰</label>
                        </div>

                        <!-- Row 3 -->
                        <div class="seat">
                            <input type="checkbox" id="seat-3-1">
                            <label for="seat-3-1" class="seat-label">۲۱</label>
                        </div>
                        <div class="seat">
                            <input type="checkbox" id="seat-3-2">
                            <label for="seat-3-2" class="seat-label">۲۲</label>
                        </div>
                        <div class="seat">
                            <input type="checkbox" id="seat-3-3">
                            <label for="seat-3-3" class="seat-label">۲۳</label>
                        </div>
                        <div class="seat">
                            <input type="checkbox" id="seat-3-4">
                            <label for="seat-3-4" class="seat-label">۲۴</label>
                        </div>
                        <div class="seat">
                            <input type="checkbox" id="seat-3-5">
                            <label for="seat-3-5" class="seat-label">۲۵</label>
                        </div>
                        <div class="seat">
                            <input type="checkbox" id="seat-3-6">
                            <label for="seat-3-6" class="seat-label">۲۶</label>
                        </div>
                        <div class="seat">
                            <input type="checkbox" id="seat-3-7" disabled>
                            <label for="seat-3-7" class="seat-label">۲۷</label>
                        </div>
                        <div class="seat">
                            <input type="checkbox" id="seat-3-8">
                            <label for="seat-3-8" class="seat-label">۲۸</label>
                        </div>
                        <div class="seat">
                            <input type="checkbox" id="seat-3-9">
                            <label for="seat-3-9" class="seat-label">۲۹</label>
                        </div>
                        <div class="seat">
                            <input type="checkbox" id="seat-3-10">
                            <label for="seat-3-10" class="seat-label">۳۰</label>
                        </div>
                    </div>

                    <div class="flex justify-center gap-6 mb-6">
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-gray-300 rounded mr-2"></div>
                            <span class="text-sm">قابل انتخاب</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-blue-600 rounded mr-2"></div>
                            <span class="text-sm">انتخاب شده</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-red-600 rounded mr-2"></div>
                            <span class="text-sm">رزرو شده</span>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between items-center border-t border-gray-200 pt-4">
                    <div>
                        <div class="text-gray-600">مجموع قیمت:</div>
                        <div class="text-xl font-bold" id="totalPrice">۰ تومان</div>
                    </div>
                    <button id="reserveBtn"
                        class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded-lg transition duration-300">رزرو</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Message -->
    <div id="flashMessage" class="flash-message">
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 ml-2" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span class="flash-message-text">رزرو با موفقیت انجام شد!</span>
        </div>
    </div>


    <!-- Back to Top Button -->
    <button id="backToTop"
        class="fixed bottom-8 right-8 bg-gradient-to-r from-blue-500 to-purple-600 text-white p-4 rounded-full shadow-xl hover:scale-110 hover:shadow-2xl transition-all duration-300 z-20 flex items-center justify-center opacity-0 pointer-events-none transform translate-y-10">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
            stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
        </svg>
    </button>

    <script>
        const btn = document.getElementById("backToTop");

        window.addEventListener("scroll", () => {
            const scrollY = window.scrollY;
            const windowHeight = window.innerHeight;
            const docHeight = document.body.scrollHeight;
            const distanceFromBottom = docHeight - (scrollY + windowHeight);

            if (scrollY > 300 && distanceFromBottom > 100) {
                btn.classList.remove("opacity-0", "pointer-events-none", "translate-y-10");
                btn.classList.add("opacity-100", "translate-y-0");
            } else {
                btn.classList.add("opacity-0", "pointer-events-none", "translate-y-10");
                btn.classList.remove("opacity-100", "translate-y-0");
            }
        });

        // عملکرد کلیک
        btn.addEventListener("click", () => {
            window.scrollTo({ top: 0, behavior: "smooth" });
        });


        // Login Modal Functionality
        const loginBtn = document.getElementById('loginBtn');
        const loginBtnMobile = document.getElementById('loginBtnMobile');
        const loginModal = document.getElementById('loginModal');
        const closeLoginModal = document.getElementById('closeLoginModal');
        const phoneInputStep = document.getElementById('phoneInputStep');
        const otpInputStep = document.getElementById('otpInputStep');
        const sendCodeBtn = document.getElementById('sendCodeBtn');
        const userPhone = document.getElementById('userPhone');
        const phone = document.getElementById('phone');
        const otpInputs = document.querySelectorAll('.otp-input');
        const verifyCodeBtn = document.getElementById('verifyCodeBtn');

        loginBtn.addEventListener('click', () => {
            loginModal.style.display = 'flex';
        });
        loginBtnMobile.addEventListener('click', () => {
            loginModal.style.display = 'flex';
        });

        closeLoginModal.addEventListener('click', () => {
            loginModal.style.display = 'none';
            phoneInputStep.style.display = 'block';
            otpInputStep.style.display = 'none';
        });

        sendCodeBtn.addEventListener('click', () => {
            if (phone.value.trim() !== '') {
                phoneInputStep.style.display = 'none';
                otpInputStep.style.display = 'block';
                userPhone.textContent = phone.value;
                startTimer();
            }
        });

        // OTP Input Functionality
        otpInputs.forEach(input => {
            input.addEventListener('input', (e) => {
                if (e.target.value.length === 1) {
                    const nextIndex = parseInt(e.target.dataset.index) + 1;
                    if (nextIndex < otpInputs.length) {
                        otpInputs[nextIndex].focus();
                    }
                }
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && e.target.value === '') {
                    const prevIndex = parseInt(e.target.dataset.index) - 1;
                    if (prevIndex >= 0) {
                        otpInputs[prevIndex].focus();
                    }
                }
            });
        });

        // Timer Functionality
        let timerInterval;
        const timer = document.getElementById('timer');

        function startTimer() {
            let seconds = 120;

            timerInterval = setInterval(() => {
                seconds--;

                const minutes = Math.floor(seconds / 60);
                const remainingSeconds = seconds % 60;

                timer.textContent = `${minutes.toString().padStart(2, '۰')}:${remainingSeconds.toString().padStart(2, '۰')}`;

                if (seconds <= 0) {
                    clearInterval(timerInterval);
                }
            }, 1000);
        }

        // Movie Modal Functionality
        const movieModal = document.getElementById('movieModal');
        const closeMovieModal = document.getElementById('closeMovieModal');
        const movieModalTitle = document.getElementById('movieModalTitle');
        const movieName = document.getElementById('movieName');
        const movieDirector = document.getElementById('movieDirector');
        const movieGenre = document.getElementById('movieGenre');
        const movieRating = document.getElementById('movieRating');
        const movieDescription = document.getElementById('movieDescription');
        const moviePoster = document.getElementById('moviePoster');

        function openMovieModal(name, director, genre, rating) {
            movieModalTitle.textContent = `جزئیات فیلم ${name}`;
            movieName.textContent = name;
            movieDirector.textContent = director;
            movieGenre.textContent = genre;
            movieRating.textContent = rating;

            // Set movie description based on the movie name
            let description = '';
            let posterColor = '';

            switch (name) {
                case 'آخرین داستان':
                    description = 'اقتباسی آزاد از داستان‌های شاهنامه فردوسی که روایتگر زندگی ضحاک ماردوش است.';
                    posterColor = 'assets/img/3.jpg';
                    break;
                case 'مطرب':
                    description = 'داستان خواننده‌ای که بعد از سال‌ها ممنوع‌الکاری تلاش می‌کند دوباره به عرصه موسیقی بازگردد.';
                    posterColor = 'assets/img/2.jpg';
                    break;
                case 'دینامیت':
                    description = 'داستان دو دوست که درگیر ماجراهای خنده‌دار می‌شوند و تلاش می‌کنند از مشکلات خود عبور کنند.';
                    posterColor = 'assets/img/1.jpg';
                    break;
                case 'قهرمان':
                    description = 'داستان مردی که در مرخصی زندان تلاش می‌کند با بازگرداندن مقداری پول، رضایت طلبکارش را جلب کند.';
                    posterColor = 'assets/img/4.jpg';
                    break;
                case 'روز صفر':
                    description = 'داستان مأموری که درگیر عملیات پیچیده‌ای برای دستگیری یک قاچاقچی بزرگ می‌شود.';
                    posterColor = 'assets/img/5.jpg';
                    break;
                case 'ابلق':
                    description = 'داستان زنی که با چالش‌های زندگی مواجه می‌شود و برای حفظ خانواده‌اش تلاش می‌کند.';
                    posterColor = 'assets/img/6.jpg';
                    break;
                case 'مردعنکوتی':
                    description = 'داستان مردعنکبوتی که با چالش‌های زندگی مواجه می‌شود و برای حفظ خانواده‌اش تلاش می‌کند.';
                    posterColor = 'assets/img/8.svg';
                    break;
                case 'بتمن':
                    description = 'داستان مردی که با چالش‌های زندگی مواجه می‌شود و برای حفظ خانواده‌اش تلاش می‌کند.';
                    posterColor = 'assets/img/7.svg';
                    break;
                case 'انتقام جویان':
                    description = 'داستان گروهی از قهرمانان که با چالش‌های نجات دنیا مواجه می‌شود.';
                    posterColor = 'assets/img/9.svg';
                    break;
                default:
                    description = 'اطلاعات بیشتر در مورد این فیلم در دسترس نیست.';
                    posterColor = 'assets/img/9.svg';
            }

            movieDescription.textContent = description;
            moviePoster.src = posterColor;

            movieModal.style.display = 'flex';
        }

        closeMovieModal.addEventListener('click', () => {
            movieModal.style.display = 'none';
        });

        // Day Selection Functionality
        const dayItems = document.querySelectorAll('.day-item');

        dayItems.forEach(item => {
            item.addEventListener('click', () => {
                dayItems.forEach(day => day.classList.remove('active'));
                item.classList.add('active');
            });
        });

        // Seat Selection Modal Functionality
        const seatSelectionModal = document.getElementById('seatSelectionModal');
        const closeSeatModal = document.getElementById('closeSeatModal');
        const seats = document.querySelectorAll('.seat input');
        const totalPrice = document.getElementById('totalPrice');
        const reserveBtn = document.getElementById('reserveBtn');
        const flashMessage = document.getElementById('flashMessage');

        function openSeatSelectionModal() {
            seatSelectionModal.style.display = 'flex';
        }

        closeSeatModal.addEventListener('click', () => {
            seatSelectionModal.style.display = 'none';
        });

        seats.forEach(seat => {
            seat.addEventListener('change', updateTotalPrice);
        });

        function updateTotalPrice() {
            const selectedSeats = document.querySelectorAll('.seat input:checked').length;
            const pricePerSeat = 110000;
            const total = selectedSeats * pricePerSeat;

            // Format the price with Persian digits and thousand separators
            const formattedPrice = total.toLocaleString().replace(/\d/g, d => '۰۱۲۳۴۵۶۷۸۹'[d]);
            totalPrice.textContent = `${formattedPrice} تومان`;
        }

        reserveBtn.addEventListener('click', () => {
            const selectedSeats = document.querySelectorAll('.seat input:checked').length;

            if (selectedSeats > 0) {
                seatSelectionModal.style.display = 'none';
                movieModal.style.display = 'none';

                flashMessage.style.display = 'block';

                setTimeout(() => {
                    flashMessage.style.display = 'none';
                }, 3000);
            }
        });

        // Close modals when clicking outside
        window.addEventListener('click', (e) => {
            if (e.target === loginModal) {
                loginModal.style.display = 'none';
            }
            if (e.target === movieModal) {
                movieModal.style.display = 'none';
            }
            if (e.target === seatSelectionModal) {
                seatSelectionModal.style.display = 'none';
            }
        });

        // Apply animation delay to movie cards
        document.querySelectorAll('.movie-card').forEach((card, index) => {
            card.style.animationDelay = `${0.1 * index}s`;
        });

        // Apply animation delay to table rows
        document.querySelectorAll('.table-row').forEach((row, index) => {
            row.style.animationDelay = `${0.1 * index}s`;
        });
    </script>