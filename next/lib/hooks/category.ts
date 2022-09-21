import { useCategoryApi } from "@/api/category";
import {
  ICategoryFormRequest,
  ICategoryFormResponse,
  ICategoryResponse,
} from "@/models/category";
import { ErrorResponse, Response } from "@/models/response";
import { AxiosError } from "axios";
import { useRouter } from "next/router";
import { useRef } from "react";
import toast from "react-hot-toast";

export const useCategory = () => {
  const dataFetchRef = useRef(false);
  const router = useRouter();
  const {
    getCategoryAction,
    createCategoryAction,
    getDetailCategoryAction,
    updateCategoryAction,
    deleteCategoryAction,
  } = useCategoryApi();

  const getCategory = async (): Promise<
    Response<ICategoryResponse[]> | boolean
  > => {
    if (dataFetchRef.current) return false;
    dataFetchRef.current = true;
    const toastId = toast.loading("Get category...");
    try {
      const response = await getCategoryAction();
      toast.dismiss(toastId);
      return response;
    } catch (e) {
      const error = e as AxiosError<ErrorResponse>;
      console.log(error);
      throw error;
    }
  };

  const createCategory = async (
    form: ICategoryFormRequest,
    setErrors: (errors: ErrorResponse) => void
  ): Promise<Response<ICategoryFormResponse | ErrorResponse>> => {
    const toastId = toast.loading("Create category...");
    try {
      const response = await createCategoryAction(form);
      toast.dismiss(toastId);
      dataFetchRef.current = false;
      toast.success("Create category success", { id: toastId });
      return response;
    } catch (e) {
      const error = e as AxiosError<ErrorResponse>;
      if (error.response) {
        const errorResponse = error.response.data as ErrorResponse;
        const errors = errorResponse
        setErrors(errors);
      }
      toast.error("Create category failed", { id: toastId });
      throw error.response?.data;
    }
  };

  const deleteCategory = async (id: number): Promise<Response<ICategoryFormResponse>> => {
    const toastId = toast.loading("Delete category...");
    try {
      const response = await deleteCategoryAction(id);
      toast.dismiss(toastId);
      dataFetchRef.current = false;
      toast.success("Delete category success", { id: toastId });
      return response;
    } catch (e) {
      const error = e as AxiosError<ErrorResponse>;
      toast.error("Delete category failed", { id: toastId });
      throw error.response?.data;
    }
  };

  const getDetailCategory = async (id: number): Promise<Response<ICategoryResponse>> => {
    const toastId = toast.loading("Get detail category...");
    try {
      const response = await getDetailCategoryAction(id);
      toast.dismiss(toastId);
      toast.success("Get detail category success", { id: toastId });
      return response;
    } catch (e) {
      const error = e as AxiosError<ErrorResponse>;
      toast.error("Get detail category failed", { id: toastId });
      throw error.response?.data;
    }
  };

  const updateCategory = async (
    id: number,
    form: ICategoryFormRequest,
    setErrors: (errors: ErrorResponse) => void
  ): Promise<Response<ICategoryFormResponse | ErrorResponse>> => {
    const toastId = toast.loading("Update category...");
    try {
      const response = await updateCategoryAction(id, form);
      toast.dismiss(toastId);
      dataFetchRef.current = false;
      toast.success("Update category success", { id: toastId });
      return response;
    } catch (e) {
      const error = e as AxiosError<ErrorResponse>;
      if (error.response) {
        const errorResponse = error.response.data as ErrorResponse;
        const errors = errorResponse
        setErrors(errors);
      }
      toast.error("Update category failed", { id: toastId });
      throw error.response?.data;
    }
  };

  return {
    getCategory,
    createCategory,
    deleteCategory,
    getDetailCategory,
    updateCategory,
  };
};
