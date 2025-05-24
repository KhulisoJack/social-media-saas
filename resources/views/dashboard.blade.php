<!-- resources/views/dashboard.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Welcome to {{ $user->brand_name }} Dashboard</h1>
        <button class="btn btn-danger" onclick="logout()">Logout</button>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Total Requests</h5>
                    <p id="totalRequests" class="display-6">0</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Saved Posts</h5>
                    <p id="totalSaved" class="display-6">0</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5>Last Generated</h5>
                    <p id="lastGenerated" class="text-muted">-</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Generation Section -->
    <div class="card mb-4">
        <div class="card-body">
            <h4 class="mb-3">Generate New Content</h4>
            <div class="input-group">
                <input type="text" id="topicInput" class="form-control" 
                       placeholder="Enter topic (e.g., 'Summer Marketing Ideas')">
                <button class="btn btn-primary" onclick="generateContent()">
                    Generate (3 Options)
                </button>
            </div>
            <div id="optionsSection" class="mt-3" style="display: none;">
                <h5>Generated Options:</h5>
                <div id="optionsContainer" class="row"></div>
            </div>
        </div>
    </div>

    <!-- Saved Posts Section -->
    <div class="card">
        <div class="card-body">
            <h4 class="mb-3">Saved Posts</h4>
            <div id="postsContainer">
                <div class="text-center">Loading posts...</div>
            </div>
        </div>
    </div>
</div>

<script>
    const API_BASE = '/api';
    const token = localStorage.getItem('auth_token');

    // Load initial data
    document.addEventListener('DOMContentLoaded', async () => {
        await loadDashboardStats();
        await loadSavedPosts();
    });

    async function loadDashboardStats() {
        try {
            const response = await fetch(`${API_BASE}/dashboard`, {
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            });
            const data = await response.json();
            
            document.getElementById('totalRequests').textContent = data.total_requests;
            document.getElementById('totalSaved').textContent = data.total_saved;
            document.getElementById('lastGenerated').textContent = 
                data.last_generated ? new Date(data.last_generated).toLocaleString() : '-';
        } catch (error) {
            alert('Failed to load dashboard stats');
        }
    }

    async function generateContent() {
        const topic = document.getElementById('topicInput').value;
        if (!topic) return alert('Please enter a topic');

        try {
            const response = await fetch(`${API_BASE}/posts/generate`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify({ topic })
            });

            if (response.status === 429) {
                return alert('You can only generate 3 times per minute');
            }

            const { options } = await response.json();
            displayOptions(options);
        } catch (error) {
            alert('Content generation failed');
        }
    }

    function displayOptions(options) {
        const container = document.getElementById('optionsContainer');
        container.innerHTML = '';
        
        options.forEach((option, index) => {
            const card = `
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5>Option ${index + 1}</h5>
                            <h6 class="text-muted">${option.title}</h6>
                            <p>${option.content}</p>
                            <button class="btn btn-sm btn-success" 
                                onclick="savePost('${option.title}', '${option.content}')">
                                Save This
                            </button>
                        </div>
                    </div>
                </div>
            `;
            container.innerHTML += card;
        });

        document.getElementById('optionsSection').style.display = 'block';
    }

    async function savePost(title, content) {
        try {
            await fetch(`${API_BASE}/posts`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify({ title, content })
            });
            await loadDashboardStats();
            await loadSavedPosts();
            alert('Post saved successfully!');
        } catch (error) {
            alert('Failed to save post');
        }
    }

    async function loadSavedPosts() {
        try {
            const response = await fetch(`${API_BASE}/posts`, {
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            });
            const posts = await response.json();
            
            const container = document.getElementById('postsContainer');
            container.innerHTML = posts.length ? '' : '<div class="text-center">No saved posts</div>';
            
            posts.forEach(post => {
                const postEl = `
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5>${post.title}</h5>
                                    <p class="mb-0">${post.content}</p>
                                    <small class="text-muted">
                                        ${new Date(post.created_at).toLocaleDateString()}
                                    </small>
                                </div>
                                <div>
                                    <button class="btn btn-sm btn-outline-secondary"
                                        onclick="editPost(${post.id})">
                                        Edit
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger"
                                        onclick="deletePost(${post.id})">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                container.innerHTML += postEl;
            });
        } catch (error) {
            alert('Failed to load posts');
        }
    }

    function logout() {
        localStorage.removeItem('auth_token');
        window.location.href = '/login';
    }

    // TODO: Implement edit/delete functionality
    async function editPost(id) {
        // Implementation for editing posts
    }

    async function deletePost(id) {
        // Implementation for deleting posts
    }
</script>

<style>
    .card {
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    #topicInput {
        max-width: 400px;
    }
    #optionsContainer .card {
        cursor: pointer;
        transition: transform 0.2s;
    }
    #optionsContainer .card:hover {
        transform: translateY(-5px);
    }
</style>
@endsection