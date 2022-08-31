import { Button } from "@/ui/Buttons";
import { Form, Input } from "@/ui/Fields";

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
