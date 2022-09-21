import {
  IProductResponse,
  IproductFormRequest,
  IProductFormResponse,
} from '@/models/product';
import { ErrorResponse, Response } from "@/models/response";
import axios from "@/utils/axios";

export const useProductApi = () => {
  const getProductAction = async (): Promise<Response<IProductResponse[]>> => {
    try {
      const response = await axios.get<Response<IProductResponse[]>>(
        "/api/master/product"
      );
      return response.data;
    } catch (error) {
      throw error
    }
  }

  const getDetailProductAction = async (id: number): Promise<Response<IProductResponse>> => {
    try {
      const response = await axios.get<Response<IProductResponse>>(
        `/api/master/product/${id}`
      );
      return response.data;
    } catch (error) {
      throw error;
    }
  }

  const deleteProductAction = async (id: number): Promise<Response<IProductFormResponse> | Error> => {
    try {
      return axios.get("/sanctum/csrf-cookie").then(async () => {
        const deleted = await axios.delete<Response<IProductFormResponse>>(
          `/api/master/product/${id}`
        );
        return deleted.data;
      });
    } catch (error) {
      throw error
    }
  }

  return {
    getProductAction,
    deleteProductAction,
    getDetailProductAction,
  }
}
