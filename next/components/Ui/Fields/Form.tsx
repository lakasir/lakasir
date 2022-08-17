import { FormEvent, useLayoutEffect } from "react";

export interface IForm {
  onSubmit: (e: FormEvent, values: any) => void;
  children: () => JSX.Element;
  method?: string;
  className?: string;
  initialValue: any;
}

const boxValues = (input: string, props: IForm) => {
  return {
    getBox: (): HTMLCollectionOf<Element> | undefined => {
      const box = document
        .querySelector("#form__lakasir")
        ?.getElementsByTagName(input);

      return box;
    },
    set: () => {
      const box = boxValues(input, props).getBox();
      for (let i = 0, len = box?.length ?? 0; i < len; i++) {
        const defaultValues = props.initialValue;
        if (box != undefined) {
          const defaultValue = defaultValues[box[i].getAttribute("name") ?? ""];
          switch (input) {
            case "input":
              if (defaultValue) {
                box[i].value = defaultValue;
              } else {
                box[i].value =
                  box[i].getAttribute("type") == "checkbox" ? false : "";
              }
              break;

            case "select":
              for (let a = 0, len = box[i].options.length; a < len; a++) {
                if (defaultValue == box[i].options[a].value) {
                  box[i].options[a].selected = true;
                }
              }
              break;

            default:
          }
        }
      }
    },
  };
};

const Form = (props: IForm): JSX.Element => {
  useLayoutEffect(() => {
    boxValues("input", props).set();
    boxValues("select", props).set();
  });
  return (
    <div>
      <form
        className={props.className}
        id="form__lakasir"
        method={props.method ?? "post"}
        onSubmit={(e) => {
          e.preventDefault();
          const input = boxValues("input", props).getBox();
          let values: any = {};
          for (let i = 0, len = input?.length ?? 0; i < len; i++) {
            if (input != undefined) {
              const name = input[i].getAttribute("name") ?? "";
              const value = input[i].value;
              if (input[i].getAttribute("type") == "checkbox") {
                values[name] = input[i].checked
              } else {
                values[name] = value;
              }
            }
          }
          const select = boxValues("select", props).getBox();
          for (let i = 0, len = select?.length ?? 0; i < len; i++) {
            if (select != undefined) {
              const name = select[i].getAttribute("name") ?? "";
              const value = select[i].value;
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
