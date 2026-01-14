<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookCategory;
use App\User;
use App\Models\Role;
use App\Models\BookTranslation;
use App\Services\PdfResizerService;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        //$this->authorize('admin_book_list');

        // $books = Book::with('translation')
        // ->orderBy('created_at', 'desc')
        // ->paginate(10);

        $query = Book::query();

        $books = $this->filters($query, $request)
        ->with(['categories', 'creator' => function ($query) {
            $query->select('id', 'full_name');
        }])
        ->withCount('comments')
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        $bookCategories = BookCategory::all();
        $adminRoleIds = Role::where('is_admin', true)->pluck('id')->toArray();
        $authors = User::select('id', 'full_name', 'role_id')->whereIn('role_id', $adminRoleIds)->get();

        $data = [
            'pageTitle' => 'Books',
            'books' => $books,
            'bookCategories' => $bookCategories,
            'authors' => $authors,
        ];

        return view(getTemplate() . '.panel.book.lists', $data);
    }

    private function filters($query, $request)
    {
        $from = $request->get('from', null);
        $to = $request->get('to', null);
        $title = $request->get('title', null);
        $category_id = $request->get('category_id', null);
        $author_id = $request->get('author_id', null);

        $query = fromAndToDateFilter($from, $to, $query, 'created_at');


        if (!empty($title)) {
            $query->whereTranslationLike('title', '%' . $title . '%');
        }

        if (!empty($category_id)) {
            $query->where('category_id', $category_id);
        }

        if (!empty($author_id)) {
            $query->where('creator_id', $author_id);
        }

        return $query;
    }

    public function create()
    {
        //$this->authorize('admin_book_create');

        $categories = BookCategory::all();

        $data = [
            'pageTitle' => 'Create New Book',
            'categories' => $categories
        ];
        return view(getTemplate() . '.panel.book.create', $data);
    }

    public function store(Request $request)
    {
        //$this->authorize('admin_book_create');

        
        $this->validate($request, [
            'locale' => 'required',
            'title' => 'required|string|max:255',
            'category_id' => 'required|numeric',
            'image_cover' => 'required|string',
            'image_path' => 'required|string',
            'type' => 'required|string',
            'price' => 'nullable',
            'description' => 'required|string',
            'content' => 'required|string',
        ]);
       
        $data = $request->all();

        $pdfService = new PdfResizerService();
        
        $pdfurl = url($data['image_path']);

        $interior = $pdfService->resizeForLulu(
            $pdfurl, // interior PDF
            false                // no full bleed
        );

        $interiorPdfPath = str_replace(public_path(), '', $interior['local_path']);
        $pageCount = $interior['page_count'];

        $cover = $pdfService->generateCoverFromPdf(
            $pdfurl, // cover PDF
            $pageCount
        );

        $coverPdfPath = str_replace(public_path(), '', $cover['local_path']);

        // Create the book
        $book = Book::create([
            'creator_id' => !empty($data['author_id']) ? $data['author_id'] : auth()->id(),
            'category_id' => $data['category_id'],
            'slug' => Book::makeSlug($data['title']),
            // 'image_cover' => $data['image_cover'],
            // 'url' => $data['image_path'],
            'image_cover' => $data['image_cover'],     // ✅ Lulu cover PDF
            'url'         => $interiorPdfPath,  // ✅ Lulu interior PDF
            'cover_pdf'   => $coverPdfPath,  
            'page_count' => $pageCount,
            'price' => $data['price'] ?? null,
            'shipping_price' => $data['shipping_price'] ?? null,
            'book_price' => $data['book_price'] ?? null,
            'type' => $data['type'],
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        if ($book) {

            BookTranslation::updateOrCreate([
                'book_id' => $book->id,
                'locale' => mb_strtolower($data['locale']),
            ], [
                'title' => $data['title'],
                'description' => $data['description'],
                'content' => $data['content'],
            ]);
        }

        return redirect('panel/book/')->with('success', 'Book created successfully.');
    }

    public function edit($id)
    {
        //$this->authorize('admin_book_edit');

        $book = Book::with('translations')->findOrFail($id);
        $categories = BookCategory::all();

        $data = [
            'pageTitle' => 'Edit Book',
            'categories' => $categories,
            'book' => $book
        ];

        
        return view(getTemplate() . '.panel.book.create', $data);
    }

    public function update(Request $request, $id)
    {
        //$this->authorize('admin_book_edit');

        $this->validate($request, [
            'locale' => 'required',
            'title' => 'required|string|max:255',
            'category_id' => 'required|numeric',
            'image_cover' => 'required|string',
            'image_path' => 'required|string',
            'type' => 'required|string',
            'price' => 'nullable',
            'description' => 'required|string',
            'content' => 'required|string',
        ]);

        $data = $request->all();
        $book = Book::findOrFail($id);

        $pdfService = new PdfResizerService();
        
        $pdfurl = url($data['image_path']);

        $interior = $pdfService->resizeForLulu(
            $pdfurl, // interior PDF
            false                // no full bleed
        );

        $interiorPdfPath = str_replace(public_path(), '', $interior['local_path']);
        $pageCount = $interior['page_count'];

        $cover = $pdfService->generateCoverFromPdf(
            $pdfurl, // cover PDF
            $pageCount
        );

        $coverPdfPath = str_replace(public_path(), '', $cover['local_path']);

        // Update the book
        $book->update([
            'creator_id' => !empty($data['author_id']) ? $data['author_id'] : auth()->id(),
            'category_id' => $data['category_id'],
            'slug' => Book::makeSlug($data['title']),
            // 'image_cover' => $data['image_cover'],
            // 'url' => $data['image_path'],
            'image_cover' => $data['image_cover'],     // ✅ Lulu cover PDF
            'url'         => $interiorPdfPath,  // ✅ Lulu interior PDF
            'cover_pdf'   => $coverPdfPath,  
            'page_count' => $pageCount,
            'price' => $data['price'] ?? null,
            'shipping_price' => $data['shipping_price'] ?? null,
            'book_price' => $data['book_price'] ?? null,
            'type' => $data['type'],
            'price' => $data['price'] ?? null,
            'updated_at' => time(),
        ]);

        BookTranslation::updateOrCreate([
            'book_id' => $book->id,
            'locale' => mb_strtolower($data['locale']),
        ], [
            'title' => $data['title'],
            'description' => $data['description'],
            'content' => $data['content'],
        ]);

        return redirect('panel/book/')->with('success', 'Book updated successfully.');
    }

    public function delete($id)
    {
        //$this->authorize('admin_book_delete');

        $book = Book::findOrFail($id);
        $book->delete();

        return redirect('panel/book/')->with('success', 'Book deleted successfully.');
    }
}