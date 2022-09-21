import {
  ICategoryResponse,
  ICategoryFormRequest,
  ICategoryFormResponse,
} from "@/models/category";
import { ErrorResponse, Response } from "@/models/response";
import axios from "@/utils/axios";
import { AxiosError } from "axios";

export const useCategoryApi = () => {
  const getCategoryAction = async (): Promise<
    Response<ICategoryResponse[]>
  > => {
    try {
      const response = await axios.get<Response<ICategoryResponse[]>>(
        "/api/master/category"
      );
      return response.data;
    } catch (error) {
      const axiosError = error as AxiosError<ErrorResponse>;
      throw axiosError.response?.data;
    }
  };

  const createCategoryAction = async (
    form: ICategoryFormRequest
  ): Promise<Response<ICategoryFormResponse>> => {
    try {
      return axios.get("/sanctum/csrf-cookie").then(async () => {
        try {
          const response = await axios.post<Response<ICategoryFormResponse>>(
            "/api/master/category",
            form
          );
          return response.data;
        } catch (error) {
          const axiosError = error as AxiosError<ErrorResponse>;
          throw axiosError;
        }
      });
    } catch (error) {
      throw error;
    }
  };

  const updateCategoryAction = async (
    id: number,
    form: ICategoryFormRequest
  ): Promise<Response<ICategoryFormResponse>> => {
    try {
      return axios.get("/sanctum/csrf-cookie").then(async () => {
        try {
          const response = await axios.put<Response<ICategoryFormResponse>>(
            `/api/master/category/${id}`,
            form
          );
          return response.data;
        } catch (error) {
          const axiosError = error as AxiosError<ErrorResponse>;
          throw axiosError
        }
      });
    } catch (error) {
      throw error;
    }
  };

  const getDetailCategoryAction = async (
    id: number
  ): Promise<Response<ICategoryResponse>> => {
    try {
      const response = await axios.get(`/api/master/category/${id}`);
      return response.data;
    } catch (error) {
      const axiosError = error as AxiosError<ErrorResponse>;
      throw axiosError.response?.data;
    }
  };

  const deleteCategoryAction = async (
    id: number
  ): Promise<Response<ICategoryFormResponse>> => {
    try {
      return axios.get("/sanctum/csrf-cookie").then(async () => {
        const response = await axios.delete<Response<ICategoryFormResponse>>(
          `/api/master/category/${id}`
        );
        return response.data;
      });
    } catch (error) {
      const axiosError = error as AxiosError<ErrorResponse>;
      throw axiosError.response?.data;
    }
  };

  return {
    getCategoryAction,
    createCategoryAction,
    updateCategoryAction,
    getDetailCategoryAction,
    deleteCategoryAction,
  };
};
