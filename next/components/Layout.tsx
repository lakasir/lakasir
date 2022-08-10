import React, { ReactText, useEffect } from "react";
import { defineCustomElements as ionDefineCustomElements } from "@ionic/core/loader";
/* Core CSS required for Ionic components to work properly */
import "@ionic/core/css/core.css";

import { JSX as LocalJSX } from "@ionic/core";
import { JSX as IoniconsJSX } from "ionicons";
import { HTMLAttributes } from "react";

type ToReact<T> = {
  [P in keyof T]?: T[P] &
    Omit<HTMLAttributes<Element>, "className"> & {
      class?: string;
      key?: ReactText;
    };
};

declare global {
  export namespace JSX {
    interface IntrinsicElements
      extends ToReact<
        LocalJSX.IntrinsicElements & IoniconsJSX.IntrinsicElements
      > {
      key?: string;
    }
  }
}

export interface LayoutProps {
  children: JSX.Element[] | string;
  title?: string;
  footer?: JSX.Element | string;
  className?: string;
}

export function Layout(props: LayoutProps): JSX.Element {
  useEffect(() => {
    ionDefineCustomElements(window);
  });
  return (
    <ion-app>
      <ion-header translucent>
        {props.title ? (
          <ion-toolbar>
            <ion-title>{props.footer}</ion-title>
          </ion-toolbar>
        ) : (
          ""
        )}
      </ion-header>

      <ion-content fullscreen>
        <div className={props.className}>{props.children}</div>
      </ion-content>
      <ion-footer class="z-40">{props.footer ?? ""}</ion-footer>
    </ion-app>
  );
}

export default Layout;
