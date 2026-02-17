import axios from 'axios';

// API Configuration
const API_BASE_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api';

// Create axios instance with default config
export const api = axios.create({
  baseURL: API_BASE_URL,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
  withCredentials: true,
});

// Request interceptor to add auth token
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('auth_token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

// Response interceptor for error handling
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      // Unauthorized - clear token and redirect to login
      localStorage.removeItem('auth_token');
      localStorage.removeItem('user');
      window.location.href = '/auth/login.html';
    }
    return Promise.reject(error);
  }
);

// Auth API
export const auth = {
  async login(email, password) {
    const response = await api.post('/auth/login', { email, password });
    if (response.data.token) {
      localStorage.setItem('auth_token', response.data.token);
      localStorage.setItem('user', JSON.stringify(response.data.user));
    }
    return response.data;
  },
  
  async register(data) {
    const response = await api.post('/auth/register', data);
    if (response.data.token) {
      localStorage.setItem('auth_token', response.data.token);
      localStorage.setItem('user', JSON.stringify(response.data.user));
    }
    return response.data;
  },
  
  async logout() {
    await api.post('/auth/logout');
    localStorage.removeItem('auth_token');
    localStorage.removeItem('user');
    window.location.href = '/auth/login.html';
  },
  
  getUser() {
    const userJson = localStorage.getItem('user');
    return userJson ? JSON.parse(userJson) : null;
  },
  
  isAuthenticated() {
    return !!localStorage.getItem('auth_token');
  },
};

// Products API
export const products = {
  async getAll(params = {}) {
    const response = await api.get('/products', { params });
    return response.data;
  },
  
  async getOne(id) {
    const response = await api.get(`/products/${id}`);
    return response.data;
  },
  
  async create(data) {
    const response = await api.post('/products', data);
    return response.data;
  },
  
  async update(id, data) {
    const response = await api.put(`/products/${id}`, data);
    return response.data;
  },
  
  async delete(id) {
    const response = await api.delete(`/products/${id}`);
    return response.data;
  },
};

// Categories API
export const categories = {
  async getAll() {
    const response = await api.get('/categories');
    return response.data;
  },
  
  async getOne(id) {
    const response = await api.get(`/categories/${id}`);
    return response.data;
  },
  
  async create(data) {
    const response = await api.post('/categories', data);
    return response.data;
  },
  
  async update(id, data) {
    const response = await api.put(`/categories/${id}`, data);
    return response.data;
  },
  
  async delete(id) {
    const response = await api.delete(`/categories/${id}`);
    return response.data;
  },
};

// Companies API
export const companies = {
  async getAll() {
    const response = await api.get('/companies');
    return response.data;
  },
  
  async getOne(id) {
    const response = await api.get(`/companies/${id}`);
    return response.data;
  },
  
  async create(data) {
    const response = await api.post('/companies', data);
    return response.data;
  },
  
  async update(id, data) {
    const response = await api.put(`/companies/${id}`, data);
    return response.data;
  },
  
  async delete(id) {
    const response = await api.delete(`/companies/${id}`);
    return response.data;
  },
};

// Orders API
export const orders = {
  // Get user's orders
  async getUserOrders(params = {}) {
    const response = await api.get('/orders', { params });
    return response.data;
  },
  
  // Get all orders (admin only)
  async getAllOrders(params = {}) {
    const response = await api.get('/admin/orders', { params });
    return response.data;
  },
  
  // Get single order
  async getOne(id) {
    const response = await api.get(`/orders/${id}`);
    return response.data;
  },
  
  // Create order
  async create(data) {
    const response = await api.post('/orders', data);
    return response.data;
  },
  
  // Update order status (admin only)
  async updateStatus(id, data) {
    const response = await api.put(`/orders/${id}`, data);
    return response.data;
  },
  
  // Cancel order
  async cancel(id) {
    const response = await api.delete(`/orders/${id}`);
    return response.data;
  },
};

export default api;
