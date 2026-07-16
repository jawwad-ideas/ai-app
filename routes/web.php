<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DocumentController;
use App\Services\EmbeddingService;
use App\Services\SimilarityService;
use App\Services\VectorService;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/documents', [DocumentController::class, 'index']);
Route::post('/documents', [DocumentController::class, 'store']);


Route::get('/search', [DocumentController::class, 'searchForm']);
Route::post('/search', [DocumentController::class, 'search']);

Route::get('/', [ChatController::class, 'index']);

Route::post('/chat', [ChatController::class, 'chat']);

Route::post('/documents', [DocumentController::class, 'store']);




// Route::get('/vector/create', function (VectorService $vector) {

//     return $vector->createCollection();

// });



// Route::get('/embedding-test', function (EmbeddingService $embeddingService) {

// $documents = [

//     [
//         'title'=>'Laravel',
//         'vector'=>[1,2,3]
//     ],

//     [
//         'title'=>'Pizza',
//         'vector'=>[8,9,10]
//     ],

//     [
//         'title'=>'Football',
//         'vector'=>[20,30,40]
//     ]

// ];    


// $best = null;
// $bestDistance = PHP_FLOAT_MAX;

// $similarity = new SimilarityService();
// $question=[1,2,4];

// foreach($documents as $document){

//     $distance = $similarity->distance(
//         $question,
//         $document['vector']
//     );

//     if($distance < $bestDistance){

//         $bestDistance = $distance;
//         $best = $document;

//     }

// }

// dd($best);





// $service = new SimilarityService();

// dd(

//     $service->distance(
//         [1,2,3],
//         [1,2,4]
//     )

// );

// $embedding = $embeddingService->embed(
    //     'Laravel is an amazing PHP framework.'
    // );

    // dd($embedding);

    // $embedding1 = $embeddingService->embed('Laravel is a PHP framework.');

    // $embedding2 = $embeddingService->embed('PHP developers use Laravel.');

    // dd([
    //     'embedding1_dimensions' => count($embedding1),
    //     'embedding2_dimensions' => count($embedding2),
    // ]);

//});
