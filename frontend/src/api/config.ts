// src/api/config.ts
import axios from 'axios';

export const baseApiUrl = 'http://localhost:5000';

// Create an Axios instance for the API
export const api = axios.create({
  baseURL: baseApiUrl,
  withCredentials: true, // Include cookies with requests
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json',
  },
});