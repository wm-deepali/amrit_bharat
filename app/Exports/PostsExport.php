<?php

namespace App\Exports;

use App\Post;
// use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Database\Eloquent\Builder;

class PostsExport implements FromQuery , WithHeadings , WithMapping
{
    use Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct($data)
    {
        $this->category = $data['category'];
        $this->subcategory = $data['subcategory'];
        $this->reporter = $data['reporter'];
    }

    public function map($post): array
    {
        return [
            $post->created_at,
            $post->categories->first()->category->name,
            $post->subcategories->first()->subcategory->name ?? 'NA',
            $post->title,
            $post->user->name ?? '',
            $post->user->contact ?? '',
            $post->views,
            $post->status,
        ];
    }

    public function headings(): array
    {
        return [
            'Created At',
            'Category',
            'Sub-category',
            'News-title',
            'Reporter',
            'Mobile-Number',
            'Views/Clicks',
            'Status',
        ];
    }

    public function query()
    {
        return Post::when($this->category,function($query,$category){
            $query->whereHas('categories', function (Builder $query) use($category) {
                $query->where('category_id',$category);
            });
        })->when($this->subcategory,function($query1,$subcategory){
            $query1->whereHas('subcategories', function (Builder $query) use($subcategory) {
                $query->where('subcategory_id',$subcategory);
            });
        })->when($this->reporter,function($query2,$reporter){
            $query2->where('user_id',$reporter);
        })->orderBy('id', 'desc');
    }
}
