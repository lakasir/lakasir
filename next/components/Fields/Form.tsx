import { FormEvent, useLayoutEffect } from "react";

export interface IForm {
  onSubmit: (e: FormEvent, values: any) => void;
  children: () => JSX.Element;
  initialValue: any;
}

const Form = (props: IForm): JSX.Element => {
  useLayoutEffect(() => {
    const input = document
      .querySelector("#form__lakasir")
      ?.getElementsByTagName("input");
    for (let i = 0, len = input?.length ?? 0; i < len; i++) {
      const defaultValues = props.initialValue;
      if (input != undefined) {
        const defaultValue = defaultValues[input[i].getAttribute("name") ?? ""];
        if (defaultValue) {
          input[i].value = defaultValue;
        }
      }
    }
  });
  return (
    <div>
      <form
        id="form__lakasir"
        method="post"
        onSubmit={(e) => {
          const input = document
            .querySelector("#form__lakasir")
            ?.getElementsByTagName("input");
          e.preventDefault();
          let values: any = {};
          for (let i = 0, len = input?.length ?? 0; i < len; i++) {
            if (input != undefined) {
              const name = input[i].getAttribute("name") ?? "";
              const value = input[i].value;
              values[name] = value;
            }
          }
          props.onSubmit(e, values);
        }}
      >
        {props.children()}
      </form>
    </div>
  );
};

export default Form;
