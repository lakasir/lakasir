import { useAuth } from "@/hooks/auth";
import { IFormForgotPasswordRequest } from "@/models/auth";
import { ErrorResponse } from "@/models/response";
import { Button } from "@/ui/Buttons";
import { Form, Input } from "@/ui/Fields";
import { Layout } from "@/ui/Layout";
import { NextPage } from "next";
import { FormEvent, useState } from "react";

interface IErrorForgotPasswordResponse {
  email?: string;
}

const ForgotPassword: NextPage = () => {
  const { forgotPassword } = useAuth();
  const [errors, setErrors] = useState<IErrorForgotPasswordResponse>({});
  const forgotPasswordSubmit = (_: FormEvent, values: IFormForgotPasswordRequest) => {
    forgotPassword(values, (error: ErrorResponse) => {
      setErrors({
        email: error.errors.email ? error.errors.email[0] : "",
      });
    });
  }
  return (
    <Layout nosavearea>
      <div className="grid gap-16">
        <p className="flex justify-center items-end h-56 text-[32px] font-semibold">
          Forgot Password
        </p>
        <Form
          className="space-y-5"
          initialValue={{
            email: "",
          }}
          onSubmit={forgotPasswordSubmit}
        >
          {() => (
            <>
              <Input name="email" type="text" label="Email" error={errors.email}/>
              <Button className="w-full py-4">Sent to Email</Button>
            </>
          )}
        </Form>
      </div>
    </Layout>
  );
};

export default ForgotPassword;
