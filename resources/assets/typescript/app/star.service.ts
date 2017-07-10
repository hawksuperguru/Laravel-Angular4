import { Injectable } from '@angular/core';

import { Http, Response,URLSearchParams, RequestOptions } from "@angular/http";
import 'rxjs/add/operator/toPromise';

import { Club } from "./model/club";
import { Player } from "./model/player";
import { Coach } from "./model/coach";

@Injectable()
export class StarService {

  public players: Player[];
  public coaches: Coach[];
  public clubs: Club[];


  public playerUrl: string = 'api/angular/player';

  constructor(private http: Http) {  }

  private static handleError(error: any): Promise<any> {
      return Promise.reject(error.message || error);
  }

  getPlayers(): Promise<Player[]> {
      let Params: URLSearchParams = new URLSearchParams();
      Params.set('Param',"1");

      let requestOption: RequestOptions = new RequestOptions();
      requestOption.search = Params;

            console.log("url");

      return this.http.get( "/api/angular/players")
      .toPromise()
      .then((response:Response) => {
        let Players = [];
        let response_data = response.json();
        console.log(response_data);

        for (let player of response_data) {
          Players.push(player);
        }
        console.log(Players);
        return Players;
      })
      .catch(StarService.handleError);
  }

  getPlayer(id: number): Promise<Player> {
      
      let url = `${this.playerUrl}/${id}`;

      return this.http.get( url )
      .toPromise()
      .then((response) => {
        console.log(response);
        let player = response.json();
        return player;
      })
      .catch(StarService.handleError);
  }

}
