<?php

namespace App\Http\Livewire;

use App\Models\Post;
use Livewire\Component;

class Posts extends Component
{
    public $posts, $title, $body, $post_id;
    public $statusDelete;
    public $isOpen = 0;

    public function create()
    {
        $this->resetInput();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function resetInput()
    {
        $this->post_id = '';
        $this->title = '';
        $this->body = '';
    }

    public function store()
    {
        $this->validate([
            'title' => 'required',
            'body' => 'required',
        ]);

        Post::updateOrCreate(['id' => $this->post_id],[
            'title' => $this->title,
            'body' => $this->body,
        ]);

        session()->flash('message', 
            $this->post_id ? 'Post Updated Successfully.' : 'Post Created Successfully.');

        $this->closeModal();
        $this->resetInput();
    }

    public function edit($id)
    {
        $post = Post::findOrFail($id);
        $this->post_id = $id;
        $this->title = $post->title;
        $this->body = $post->body;

        $this->openModal();
    }

    public function delete($id)
    {
        Post::find($id)->delete();
        session()->flash('message', 'Post Created Successfully.');

    }

    public function statusDelete($id)
    {
        $this->statusDelete = $id;
    }

    public function cancelDelete()
    {
        $this->statusDelete = null;
    }

    public function render()
    {
        $this->posts = Post::all();
        return view('livewire.posts')->layout('layouts.app');
    }
}
