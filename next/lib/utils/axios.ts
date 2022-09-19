import Axios, { AxiosRequestConfig } from "axios";
import nextConfig from "next/config";

const axiosInstance = Axios.create({
  baseURL: nextConfig().publicRuntimeConfig.APP_URL,
  withCredentials: true,
  headers: {
    "Accept": "application/json",
    "Content-type": "application/json",
  },
  xsrfHeaderName: "X-CSRF-TOKEN",
  xsrfCookieName: "XSRF-TOKEN",
});

axiosInstance.interceptors.request.use(
  async (config: AxiosRequestConfig) => {
    let token = null;
    try {
      token = localStorage.getItem('token');
    } catch (err) {
      console.log('Token not found');
      throw err;
    }

    if (token && config.headers) {
      config.headers['authorization'] = `Bearer ${token}`;
    }
    return config;
  },
  (error) => Promise.reject(error)
);

export default axiosInstance;
