import type { NextPage } from "next";
import Link from "next/link";
import { FormEvent } from "react";
import PasswordField from "../../components/Auth/PasswordField";
import Button from "../../components/Ui/Buttons/Button";
import Checkbox from "../../components/Ui/Fields/Checkbox";
import Form from "../../components/Ui/Fields/Form";
import Input from "../../components/Ui/Fields/Input";
import Layout from "../../components/Ui/Layout";

const Login: NextPage = () => {
  const loginSubmit = (e: FormEvent, values: any) => {
    console.log(values);
  };
  return (
    <Layout>
      <div className="mx-auto w-11/12">
        <div className="grid gap-16">
          <p className="flex justify-center items-end h-56 text-[32px] font-semibold">
            Sign In <span className="ml-2 text-lakasir-primary"> LAKASIR</span>
          </p>
          <Form
            className="space-y-5"
            initialValue={{
              email: "sheenazien08@gmail.com",
              password: "",
            }}
            onSubmit={loginSubmit}
          >
            {() => (
              <>
                <Input
                  name={"email"}
                  type={"text"}
                  label={
                    <>
                      Username or Email<span className="text-red-500">*</span>
                    </>
                  }
                />
                <PasswordField
                  label={
                    <>
                      Password<span className="text-red-500">*</span>
                    </>
                  }
                  name={"password"}
                />
                <Checkbox name={"remember_me"} label={"Remember Me"} />
                <Button className="w-full py-4">Sign in</Button>
              </>
            )}
          </Form>
          <div>
            <Link href="/auth/forgot-password">
              <a className="text-lakasir-primary font-medium flex justify-center text-center mb-7">
                Forgot the password?
              </a>
            </Link>
            <p className="font-medium text-center">
              Dont have an account?{" "}
              <Link href="/auth/register">
                <a className="text-lakasir-primary">Sign up</a>
              </Link>
            </p>
          </div>
        </div>
      </div>
    </Layout>
  );
};

export default Login;
