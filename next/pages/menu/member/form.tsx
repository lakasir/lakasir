import Image from "next/image";
import Link from "next/link";
import Button from "../../../components/Ui/Buttons/Button";
import Form from "../../../components/Ui/Fields/Form";
import Input from "../../../components/Ui/Fields/Input";
import { Select } from "../../../components/Ui/Fields/Select";

interface IFormMemberInterface {
  form?: MemberData;
}

interface MemberData {
  name?: string;
  code?: number;
  email?: number;
}

const FormMember = (props: IFormMemberInterface) => {
  return (
    <Form
      className="space-y-8"
      initialValue={{
        ...props.form,
      }}
      onSubmit={(e, values) => console.log(values)}
    >
      {() => (
        <>
          <Input
            name={"name"}
            type={"text"}
            label={
              <>
                Name<span className="text-red-500">*</span>
              </>
            }
          />
          <Input
            name={"email"}
            type={"text"}
            label={
              <>
                Email<span className="text-red-500">*</span>
              </>
            }
          />
          <Input
            name={"code"}
            type={"text"}
            placeholder={"Leave it blank to use code generator from app"}
            label={
              <>
                Code<span className="text-red-500">*</span>
              </>
            }
          />
          <Button className="w-full py-4">Save</Button>
        </>
      )}
    </Form>
  );
};

export default FormMember;
