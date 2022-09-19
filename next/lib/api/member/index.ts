import {
  IMemberFormResponse,
  IMemberResponse,
  IMemberFormRequest,
} from "@/models/member";
import { ErrorResponse, Response } from "@/models/response";
import axios from "@/utils/axios";
import { AxiosError } from "axios";

export const useMemberApi = () => {
  const getMemberAction = async (): Promise<Response<IMemberResponse[]>> => {
    const response = await axios.get<Response<IMemberResponse[]>>(
      "/api/master/member"
    );
    return response.data;
  };

  const createMemberAction = async (
    data: IMemberFormRequest
  ): Promise<Response<IMemberFormResponse> | ErrorResponse> => {
    try {
      return axios
        .get("/sanctum/csrf-cookie")
        .then(async () => {
          try {
            const response = await axios.post<Response<IMemberFormResponse>>(
              "/api/master/member",
              data
            );
            if (![200, 201].includes(response.status)) {
              throw new Error("Error");
            }

            return response;
          } catch (e) {
            const error = e as AxiosError<ErrorResponse>;
            throw error;
          }
        })
        .catch((error) => {
          return error;
        });
    } catch (error) {
      console.log("Ini Error");
      console.log((error as AxiosError<ErrorResponse>).response);
      throw error as ErrorResponse;
    }
  };

  const getDetailMemberAction = async (
    id: number
  ): Promise<Response<IMemberFormResponse>> => {
    const response = await axios.get<Response<IMemberFormResponse>>(
      `/api/master/member/${id}`
    );
    return response.data;
  };

  const updateMemberAction = async (
    data: IMemberFormRequest,
    id: number
  ): Promise<Response<IMemberFormResponse> | Error> => {
    try {
      return axios
        .get("/sanctum/csrf-cookie")
        .then(async () => {
          const response = await axios.put<Response<IMemberFormResponse>>(
            `/api/master/member/${id}`,
            data
          );
          return response.data;
        })
        .catch((error) => {
          return error;
        });
    } catch (error) {
      return error as Error;
    }
  };

  const deleteMemberAction = async (): Promise<
    Response<IMemberFormResponse> | Error
  > => {
    try {
      return axios
        .get("/sanctum/csrf-cookie")
        .then(async () => {
          const response = await axios.delete<Response<IMemberFormResponse>>(
            "/api/master/member"
          );
          return response.data;
        })
        .catch((error) => {
          return error;
        });
    } catch (error) {
      return error as Error;
    }
  };

  return {
    getMemberAction,
    createMemberAction,
    updateMemberAction,
    deleteMemberAction,
    getDetailMemberAction,
  };
};
